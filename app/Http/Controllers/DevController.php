<?php

namespace App\Http\Controllers;

use App\Models\Dev;

use App\Models\Ga;
use App\Models\Ga_dev;
use App\Models\Profile;
use App\Models\ProfileV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class DevController extends Controller
{
    public function index()
    {
        $ga_name = Ga::orderBy('ga_name','asc')->get();
        $ga_dev = Ga_dev::orderBy('gmail','asc')->get();
        $profiles = ProfileV2::orderBy('profile_name','asc')->get();
        return view('dev.index',compact(['ga_name','ga_dev','profiles']));
    }
    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Dev::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Dev::select('count(*) as allcount')
            ->with('ga','gadev')
            ->where('id_ga', 'like', '%' . $searchValue . '%')
            ->orWhere('store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('dev_name', 'like', '%' . $searchValue . '%')
            ->count();

        //310


        // Get records, also we have included search filter as well
        $records = Dev::orderBy($columnName, $columnSortOrder)
            ->with('ga','gadev','gadev1','gadev2','project')
            ->orWhere('ngocphandang_dev.store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev.dev_name', 'like', '%' . $searchValue . '%')
//            ->orWhere('ngocphandang_gadev.gmail', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev.info_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev.status', 'like', '%' . $searchValue . '%')
            ->orwhereHas('ga', function ($q) use ($searchValue) {
                $q->where('ga_name','like', '%' . $searchValue . '%');
            })
            ->orwhereHas('gadev', function ($q) use ($searchValue) {
                $q->where('gmail','like', '%' . $searchValue . '%');
            })
            ->select('ngocphandang_dev.*')
//            ->withCount('project')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editDev('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevice"><i class="ti-trash"></i></a>';

            if($record->info_logo == null ){
                $logo =  '<img width="60px" height="60px" src="assets\images\logo-member.jpg">';
            }else{
                $logo =  '<img width="60px" height="60px" src='.$record->info_logo.'>';
            }

            if($record->info_andress == null ){
                $info_phone =  '<span style="color: red;font-size: medium">'.$record->info_phone.'</span>';
            } else{
                $info_phone = '<span style="color: green">'.$record->info_phone.'</span>';
            }
            if($record->gadev){
                $gmail = '<span>'.$record->gadev->gmail.' - <span style="font-style: italic"> '.$record->gadev->vpn_iplogin.'</span></span>';
            }
            if($record->gadev1){
                $gmail1 = '<p style="margin: auto" class="text-muted ">'.$record->gadev->gmail.' - <span style="font-style: italic"> '.$record->gadev->vpn_iplogin.'</span></p>';
            }
            if($record->gadev2){
                $gmail2 = '<p style="margin: auto"class="text-muted ">'.$record->gadev->gmail.' - <span style="font-style: italic"> '.$record->gadev->vpn_iplogin.'</span></p>';
            }
            if($record->id_ga == null ){
                $ga_name =  '<span class="badge badge-dark">Chưa có</span>';
            }else{
                $ga_name =$record->ga->ga_name;
            }
            if($record->status == 0){
                $status =  '<span class="badge badge-dark">Chưa xử dụng</span>';
            }
            if($record->status == 1){
                $status = '<span class="badge badge-primary">Đang phát triển</span>';
            }
            if($record->status == 2){
                $status = '<span class="badge badge-warning">Đóng</span>';
            }
            if($record->status == 3){
                $status = '<span class="badge badge-danger">Suspend</span>';
            }

            if($record['info_url'] !== Null){
                $info_url = '<a  target= _blank href="'.$record["info_url"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_url = "<i style='color:red;' class='ti-close h5'></i>";
            }
            if($record['info_web'] !== Null){
                $info_web = '<a  target= _blank href="'.$record["info_web"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_web = "<i style='color:red;' class='ti-close h5'></i>";
            }
            if($record['info_fanpage'] !== Null){
                $info_fanpage = '<a  target= _blank href="'.$record["info_fanpage"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_fanpage= "<i style='color:red;' class='ti-close h5'></i>";
            }
            if($record['info_policydev'] !== Null){
                $info_policydev = '<a  target= _blank href="'.$record["info_policydev"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_policydev = "<i style='color:red;' class='ti-close h5'></i>";
            }

            if($record->thuoc_tinh !=0){
                $thuoc_tinh = '<img height="70px" src="img/icon/profile.png">';
            }else{
                $thuoc_tinh = '<img height="70px" src="img/icon/office-building.png">';
            }
            $release = $check = 0;
            foreach ($record->project as $ch){
                $ch->Chplay_status == 1 ? $release ++ : $check++;
            }
            $data_arr[] = array(
//                "info_logo" => $logo,
                "id_ga" => $logo.'<br>'.$ga_name,
                "dev_name" => '<a href="/project?q=dev_chplay&id='.$record->id.'"> <span>'.$record->dev_name.'</span></a>
                                 <p style="margin: auto"class="text-muted ">'.$record->store_name.'</p>',
                "gmail_gadev_chinh"=> @$gmail.@$gmail1.@$gmail2,
                "project_count" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "thuoc_tinh" => $thuoc_tinh.'<br>'.$record->pass .'<br>'.$info_phone,
                "info_url"=> $info_url .' '. $info_web.' '. $info_fanpage.' '. $info_policydev,
                "status"=> $status,
                "action"=> $btn,
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }
    public function create(Request  $request)
    {
        $rules = [
            'store_name' =>'unique:ngocphandang_dev,store_name',
            'dev_name' =>'unique:ngocphandang_dev,dev_name',
            'gmail_gadev_chinh' =>'required|not_in:0',
        ];
        $message = [
            'dev_name.unique'=>'Dev name đã tồn tại',
            'store_name.unique'=>'Store name tồn tại',
            'gmail_gadev_chinh.not_in'=>'Vui lòng chọn Email',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Dev();
        $data['dev_name'] = $request->dev_name;
        $data['id_ga'] = $request->id_ga;
        $data['store_name'] = $request->store_name;
        $data['ma_hoa_don'] = $request->ma_hoa_don;
        $data['gmail_gadev_chinh'] = $request->gmail_gadev_chinh;
        $data['gmail_gadev_phu_1'] = $request->gmail_gadev_phu_1;
        $data['gmail_gadev_phu_2'] = $request->gmail_gadev_phu_2;
        $data['info_phone'] = $request->info_phone;
        $data['pass'] = $request->pass;
        $data['profile_info'] = $request->profile_info;
        $data['info_andress'] = $request->info_andress;
        $data['info_company'] = $request->info_company;
        $data['note'] = $request->note;
        $data['info_url'] = $request->info_url;
        $data['info_logo'] = $request->info_logo;
        $data['info_banner'] = $request->info_banner;
        $data['info_policydev'] = $request->info_policydev;
        $data['info_fanpage'] = $request->info_fanpage;
        $data['info_web'] = $request->info_web;
        $data['thuoc_tinh'] = $request->attribute1;
        $data['status'] = 0;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function edit($id)
    {
        $dev = Dev::find($id);
        return Response::json($dev);
    }
    public function update(Request $request)
    {
        $id = $request->dev_id;
        $rules = [
            'store_name' =>'unique:ngocphandang_dev,store_name,'.$id.',id',
            'dev_name' =>'unique:ngocphandang_dev,dev_name,'.$id.',id',
            'id_ga' =>'required|not_in:0',
            'gmail_gadev_chinh' =>'required|not_in:0',
        ];
        $message = [
            'dev_name.unique'=>'Dev name đã tồn tại',
            'store_name.unique'=>'Store name tồn tại',
            'id_ga.not_in'=>'Vui lòng chọn Ga Name',
            'gmail_gadev_chinh.not_in'=>'Vui lòng chọn Email',

        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Dev::find($id);
        $data->dev_name = $request->dev_name;
        $data->id_ga = $request->id_ga;
        $data->store_name = $request->store_name;
        $data->ma_hoa_don = $request->ma_hoa_don;
        $data->gmail_gadev_chinh = $request->gmail_gadev_chinh;
        $data->gmail_gadev_phu_1 = $request->gmail_gadev_phu_1;
        $data->gmail_gadev_phu_2 = $request->gmail_gadev_phu_2;
        $data->info_phone = $request->info_phone;
        $data->pass = $request->pass;
        $data->profile_info = $request->profile_info;
        $data->info_andress=  $request->info_andress ;
        $data->info_company=  $request->info_company ;
        $data->note= $request->note;
        $data->thuoc_tinh= $request->attribute1;
        $data->info_url = $request->info_url;
        $data->info_logo = $request->info_logo;
        $data->info_banner = $request->info_banner;
        $data->info_policydev = $request->info_policydev;
        $data->info_fanpage = $request->info_fanpage;
        $data->info_web = $request->info_web;
        $data->status = $request->status;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        Dev::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }


    public function checkProject(){

    }

}
