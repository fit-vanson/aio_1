<?php

namespace App\Http\Controllers;

use App\Models\Da;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DaController extends Controller
{
    public function index(Request $request)
    {
        $da =  Da::with('project')->latest('id')->get();
        if ($request->ajax()) {
            $data = Da::with('project')->latest('id')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-warning btn-sm editDa"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDa"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('link_store_vietmmo',function ($row){
                    if(isset($row->link_store_vietmmo)){
                        return '<a href="'.$row->link_store_vietmmo.'" target="_blank">Link </a>';
                    }
                })
                ->editColumn('ma_da',function ($row){
                    return '<a href="/project?q=ma_da&id='.$row->id.'" >'.$row->ma_da.'  - ('.count($row->project).')</a>';
                })
                ->rawColumns(['action','link_store_vietmmo','ma_da'])
                ->make(true);
        }
        return view('da.index',compact('da'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        $rules = [
            'ma_da' =>'unique:ngocphandang_da,ma_da'
        ];
        $message = [
            'ma_da.unique'=>'Tên dự đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Da();
        $data['ma_da'] = $request->ma_da;
        $data['chude'] = $request->chude;
        $data['keywords'] = $request->keywords;
        $data['link_store_vietmmo'] = $request->link_store_vietmmo;
        $data['note'] = $request->note;
        $data->save();
        $allDa  = Da::latest()->get();
        return response()->json([
            'success'=>'Thêm mới thành công',
            'du_an' => $allDa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
        $da = Da::find($id);
        return response()->json($da);
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
        $id = $request->da_id;
        $rules = [
            'ma_da' =>'unique:ngocphandang_da,ma_da,'.$id.',id',
        ];
        $message = [
            'ma_da.unique'=>'Tên Dự án đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Da::find($id);
        $data->ma_da =$request->ma_da;
        $data->chude =$request->chude;
        $data->keywords =$request->keywords;
        $data->link_store_vietmmo =$request->link_store_vietmmo;
        $data->note =$request->note;
        $data->save();
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
        Da::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
