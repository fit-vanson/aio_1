<?php

namespace App\Http\Controllers;

use App\Models\Dev;

use App\Models\Dev_Xiaomi;
use App\Models\Ga;
use App\Models\Ga_dev;
use App\Models\Profile;
use App\Models\ProfileV2;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DevXiaomiController extends Controller
{
    public function index()
    {
        $ga_name = Ga::latest('id')->get();
        $ga_dev = Ga_dev::latest('id')->get();
        $profiles = ProfileV2::orderBy('profile_name','asc')->get();
        return view('dev-xiaomi.index',compact(['ga_name','ga_dev','profiles']));
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
        $totalRecords = Dev_Xiaomi::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Dev_Xiaomi::select('count(*) as allcount')
//            ->leftjoin('ngocphandang_ga','ngocphandang_ga.id','=','ngocphandang_dev_xiaomi.xiaomi_ga_name')
//            ->leftjoin('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_dev_xiaomi.xiaomi_email')
//            ->orwhere('ngocphandang_ga.ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_dev_name', 'like', '%' . $searchValue . '%')
//            ->orWhere('ngocphandang_gadev.gmail', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Dev_Xiaomi::orderBy($columnName, $columnSortOrder)
            ->with('ga','gadev','project')
//            ->leftjoin('ngocphandang_ga','ngocphandang_ga.id','=','ngocphandang_dev_xiaomi.xiaomi_ga_name')
//            ->leftjoin('ngocphandang_gadev','ngocphandang_gadev.id','=','ngocphandang_dev_xiaomi.xiaomi_email')
//            ->orwhere('ngocphandang_ga.ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_dev_name', 'like', '%' . $searchValue . '%')
//            ->orWhere('ngocphandang_gadev.gmail', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_dev_xiaomi.xiaomi_note', 'like', '%' . $searchValue . '%')
            ->select('ngocphandang_dev_xiaomi.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();



        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="editDevxiaomi('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevxiaomi"><i class="ti-trash"></i></a>';
            $email =$record->gadev->gmail;
            if($record->xiaomi_status == 0){
                $status =  '<span class="badge badge-dark">Chưa xử dụng</span>';
            }
            if($record->xiaomi_status == 1){
                $status = '<span class="badge badge-primary">Đang phát triển</span>';
            }
            if($record->xiaomi_status == 2){
                $status = '<span class="badge badge-warning">Đóng</span>';
            }
            if($record->xiaomi_status == 3){
                $status = '<span class="badge badge-danger">Suspend</span>';
            }


            if($record->xiaomi_ga_name == 0 ){
                $ga_name =  '<span class="badge badge-dark">Chưa có</span>';
            }else{
                $ga_name = $record->ga->ga_name;
            }

            if($record->xiaomi_attribute !=0){
                $thuoc_tinh = '<img height="70px" src="img/icon/profile.png">';
            }else{
                $thuoc_tinh = '<img height="70px" src="img/icon/office-building.png">';
            }
            $release = $check = 0;
            foreach ($record->project as $ch){
                $ch->Xiaomi_status == 1 ? $release ++ : $check++;
            }


            $data_arr[] = array(
                'xiaomi_attribute' => $thuoc_tinh,
                "xiaomi_ga_name" => $ga_name,
                "xiaomi_dev_name" => '<a href="/project?q=dev_xiaomi&id='.$record->id.'"> <span>'.$record->xiaomi_dev_name.'</span></a>',
                "xiaomi_store_name" => $record->xiaomi_store_name,
                "xiaomi_email"=>$email.'<p style="margin: auto" class="text-muted ">'.$record->xiaomi_pass .'</p>',
                "project" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "xiaomi_status"=>$status,
                "xiaomi_note"=>$record->xiaomi_note,
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
            'xiaomi_store_name' =>'unique:ngocphandang_dev_xiaomi,xiaomi_store_name',
            'xiaomi_dev_name' =>'unique:ngocphandang_dev_xiaomi,xiaomi_dev_name',
            'xiaomi_email' =>'required|not_in:0',
        ];
        $message = [
            'xiaomi_dev_name.unique'=>'Dev name tồn tại',
            'xiaomi_store_name.unique'=>'Store name đã tồn tại',
            'xiaomi_email.not_in'=>'Vui lòng chọn Email'
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Dev_Xiaomi();
        $data['xiaomi_ga_name'] = $request->xiaomi_ga_name;
        $data['xiaomi_email'] = $request->xiaomi_email;
        $data['xiaomi_dev_name'] = $request->xiaomi_dev_name;
        $data['xiaomi_store_name'] = $request->xiaomi_store_name;
        $data['xiaomi_phone'] = $request->xiaomi_phone;
        $data['xiaomi_profile_info'] = $request->xiaomi_profile_info;
        $data['xiaomi_company'] = $request->xiaomi_company;
        $data['xiaomi_add'] = $request->xiaomi_add;
        $data['xiaomi_pass'] = $request->xiaomi_pass;
        $data['xiaomi_status'] = $request->xiaomi_status;
        $data['xiaomi_note'] = $request->xiaomi_note;
        $data['xiaomi_attribute'] = $request->attribute;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function edit($id)
    {
        $dev = Dev_Xiaomi::find($id);
        return Response::json($dev);
    }
    public function update(Request $request)
    {

        $id = $request->id;
        $rules = [
            'xiaomi_store_name' =>'unique:ngocphandang_dev_xiaomi,xiaomi_store_name,'.$id.',id',
            'xiaomi_dev_name' =>'unique:ngocphandang_dev_xiaomi,xiaomi_dev_name,'.$id.',id',

            'xiaomi_email' =>'required|not_in:0',
        ];
        $message = [
            'xiaomi_dev_name.unique'=>'Dev name tồn tại',
            'xiaomi_store_name.unique'=>'Store name đã tồn tại',
            'xiaomi_email.not_in'=>'Vui lòng chọn Email'
        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Dev_Xiaomi::find($id);
        $data->xiaomi_ga_name = $request->xiaomi_ga_name;
        $data->xiaomi_email = $request->xiaomi_email;
        $data->xiaomi_dev_name = $request->xiaomi_dev_name;
        $data->xiaomi_store_name = $request->xiaomi_store_name;
        $data->xiaomi_profile_info = $request->xiaomi_profile_info;
        $data->xiaomi_phone = $request->xiaomi_phone;
        $data->xiaomi_company = $request->xiaomi_company;
        $data->xiaomi_add = $request->xiaomi_add;
        $data->xiaomi_pass = $request->xiaomi_pass;
        $data->xiaomi_status = $request->xiaomi_status;
        $data->xiaomi_note = $request->xiaomi_note;
        $data->xiaomi_attribute = $request->attribute;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        Dev_Xiaomi::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
