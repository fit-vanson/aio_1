<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{


//    private $role;
    private $permission;
    public function __construct(Permission $permission, Role $role)
    {
        $this->permission = $permission;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $permisions = Permission::latest()->get();
        if ($request->ajax()) {
            $data = Permission::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editPer('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deletePer"><i class="ti-trash"></i></a>';

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('permission.index',compact(['permisions']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        foreach ($request->module_child as $item){
            $permission = Permission::create([
                'name'=> $item.' '.$request->module_parent,
                'display_name'=>$item.' '.$request->module_parent,
                'key_code' => str_replace('-','_',Str::slug($item.'_'.$request->module_parent)),
            ]);
        }
        return response()->json(['success'=>'Thêm mới thành công']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Permission::find($id);

        return response()->json([$role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->permission_id;
        $rules = [
            'name' =>'required|unique:permissions,name,'.$id.',id',
            'display_name' =>'required',

        ];
        $message = [
            'name.unique'=>'Tên quyền đã tồn tại',
            'name.required'=>'Tên quyền không để trống',
            'display_name.required'=>'Mô tả không để trống',
        ];
//
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
            $this->permission->find($id)->update([
                'name' => $request->name,
                'display_name'=> $request->name,
                'key_code' =>str_replace('-','_',Str::slug($request->name))
            ]);
            return response()->json(['success'=>'Cập nhật thành công']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Permission::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

}
