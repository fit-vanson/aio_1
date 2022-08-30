<?php

namespace App\Http\Controllers;


use App\Models\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GaController extends Controller
{
    public function index(Request $request)
    {
        $ga= DB::table('ngocphandang_ga')
            ->rightJoin('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_ga.gmail_gadev_chinh')
            ->get();
        if ($request->ajax()) {
            $data = Ga::latest('id')->get();;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editGa('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//                    $btn = $btn. ' <a href="javascript:void(0)" onclick="showGa('.$row->id.')" class="btn btn-success"><i class="ti-eye"></i></a>';
                    $btn = $btn. ' <a href="/ga/show?id='.$row->id.'" target="_blank" class="btn btn-success"><i class="ti-eye"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('gmail_gadev_chinh', function($data){
                    $gmail = DB::table('ngocphandang_ga')
                        ->join('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_ga.gmail_gadev_chinh')
                        ->where('ngocphandang_gadev.id',$data->gmail_gadev_chinh)
                        ->first();
                    $gmail1 = DB::table('ngocphandang_ga')
                        ->join('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_ga.gmail_gadev_phu_1')
                        ->where('ngocphandang_gadev.id',$data->gmail_gadev_phu_1)
                        ->first();
                    $gmail2 = DB::table('ngocphandang_ga')
                        ->join('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_ga.gmail_gadev_phu_2')
                        ->where('ngocphandang_gadev.id',$data->gmail_gadev_phu_2)
                        ->first();
                    if($gmail != null){
                        $gmail = '<span>'.$gmail->gmail.' - <span style="font-style: italic"> '.$gmail->vpn_iplogin.'</span></span>';
                    }
                    if($gmail1 != null){
                        $gmail1 = '<p style="margin: auto" class="text-muted ">'.$gmail1->gmail.' - <span style="font-style: italic"> '.$gmail1->vpn_iplogin.'</span></p>';
                    }
                    if($gmail2 != null){
                        $gmail2 = '<p style="margin: auto"class="text-muted ">'.$gmail2->gmail.' - <span style="font-style: italic"> '.$gmail2->vpn_iplogin.'</span></p>';
                    }
                    return $gmail.$gmail1.$gmail2;
                })
                ->editColumn('info_phone', function($data){
                    if($data->info_andress == null ){
                        return '<span style="color: red;font-size: medium">'.$data->info_phone.'</span>';
                    }
                    return '<span style="color: green">'.$data->info_phone.'</span>';
                })

                ->rawColumns(['action','gmail_gadev_chinh','info_phone'])
                ->make(true);
        }
        return view('ga.index',compact(['ga']));
    }

    public function create(Request  $request)
    {


        $rules = [
            'ga_name' =>'unique:ngocphandang_ga,ga_name',

        ];
        $message = [
            'ga_name.unique'=>'Tên đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Ga();
        $data['ga_name'] = $request->ga_name;
        $data['gmail_gadev_chinh'] = $request->gmail_gadev_chinh;
        $data['gmail_gadev_phu_1'] = $request->gmail_gadev_phu_1;
        $data['gmail_gadev_phu_2'] = $request->gmail_gadev_phu_2;
        $data['info_phone'] = $request->info_phone;
        $data['info_andress'] = $request->info_andress;
        $data['payment'] = $request->payment;
        $data['app_ads'] = $request->app_ads;
        $data['note'] = $request->note;
        $data['status'] = 0;
        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }


    public function edit($id)
    {
        $dev = Ga::find($id);
        return response()->json($dev);
    }

    public function update(Request $request)
    {

        $id = $request->ga_id;
        $rules = [
            'ga_name' =>'unique:ngocphandang_ga,ga_name,'.$id.',id',
        ];
        $message = [
            'ga_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Ga::find($id);
        $data->ga_name = $request->ga_name;
        $data->gmail_gadev_chinh = $request->gmail_gadev_chinh;
        $data->gmail_gadev_phu_1 = $request->gmail_gadev_phu_1;
        $data->gmail_gadev_phu_2 = $request->gmail_gadev_phu_2;
        $data->info_phone = $request->info_phone;
        $data->info_andress= $request->info_andress;
        $data->payment = $request->payment;
        $data->app_ads = $request->app_ads;
        $data->note = $request->note;
        $data->status = $request->status;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function delete($id)
    {
        Ga::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
    public function showDev($id){
        $dev = DB::table('ngocphandang_ga')
            ->join('ngocphandang_dev','ngocphandang_ga.id','=','ngocphandang_dev.id_ga')
            ->where('ngocphandang_dev.id_ga',$id)
            ->get();
        return response()->json($dev);
    }

    public function show(Request $request){

        if (isset($request->id)){
            $ga_detail = Ga::with(
                'gadev','gadev1','gadev2',
                'dev.gadev','dev_amazon.gadev','dev_samsung.gadev','dev_xiaomi.gadev','dev_oppo.gadev','dev_vivo.ga_dev','dev_huawei.gadev',
                'dev.project','dev_amazon.project','dev_samsung.project','dev_xiaomi.project','dev_oppo.project','dev_vivo.project','dev_huawei.project',
                'dev.project','dev_amazon.project','dev_samsung.project','dev_xiaomi.project','dev_oppo.project','dev_vivo.project','dev_huawei.project'
            )->find($request->id);
        }
//        dd($ga_detail);
        return view('ga.show',compact('ga_detail'));


    }
}
