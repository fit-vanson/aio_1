<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    private $permision;
    public $role;


    public function __construct(Permission $permision, Role $role)
    {
        $this->permision = $permision;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

//        $permisssions = Permission::all();
        $permissions = $this->permision->all();
        $role = Role::latest('id')->get();
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editRole('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteRole"><i class="ti-trash"></i></a>';

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('role.index',compact(['role','permissions']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {



        $rules = [
            'name' =>'required|unique:roles,name,',
        ];
        $message = [
            'name.unique'=>'Tên vai trò đã tồn tại',
            'name.required'=>'Tên vai trò không để trống',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

            $role = $this->role->create([
                'name' => $request->name,
                'display_name'=> $request->display_name,
            ]);
            $permissionIds = $request->permission_id;

            $role->permissions()->attach($permissionIds);

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
        $role = Role::find($id);
        $permissionOfRole = $role->permissions;

        return response()->json([$role,$permissionOfRole]);
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

        $id = $request->role_id;
        $rules = [
            'name' =>'required|unique:roles,name,'.$id.',id',
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

        $this->role->find($id)->update([
            'name' => $request->name,
            'display_name'=> $request->display_name,
        ]);
        $permissionIds = $request->permission_id;
        $role = $this->role->find($id);
        $role->permissions()->sync($permissionIds);
        return response()->json(['success'=>'Update thành công']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Role::find($id)->delete();
        return response()->json(['success'=>'Xóa người dùng.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

}
