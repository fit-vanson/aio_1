<?php

namespace App\Http\Controllers;

use App\Models\Dev;

use App\Models\Dev_Samsung;
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

class DevSamsungController extends Controller
{
    public function index()
    {
        $ga_name = Ga::latest('id')->get();
        $ga_dev = Ga_dev::latest('id')->get();
        $profiles = ProfileV2::orderBy('profile_name','asc')->get();
        return view('dev-samsung.index',compact(['ga_name','ga_dev','profiles']));
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
        $totalRecords = Dev_Samsung::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Dev_Samsung::select('count(*) as allcount')
            ->where('samsung_ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_dev_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_email', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_status', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Dev_Samsung::orderBy($columnName, $columnSortOrder)
            ->with('gadev','ga','project')
            ->where('samsung_ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_dev_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_email', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_status', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_note', 'like', '%' . $searchValue . '%')
            ->select('ngocphandang_dev_samsung.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editDevSamsung('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevSamsung"><i class="ti-trash"></i></a>';



            if($record->samsung_status == 0){
                $status =  '<span class="badge badge-dark">Chưa xử dụng</span>';
            }
            if($record->samsung_status == 1){
                $status = '<span class="badge badge-primary">Đang phát triển</span>';
            }
            if($record->samsung_status == 2){
                $status = '<span class="badge badge-warning">Đóng</span>';
            }
            if($record->samsung_status == 3){
                $status = '<span class="badge badge-danger">Suspend</span>';
            }


            if($record->samsung_ga_name == 0 ){
                $ga_name =  '<span class="badge badge-dark">Chưa có</span>';
            }else{
                $ga_name = $record->ga->ga_name;
            }

            if($record->samsung_attribute !=0){
                $thuoc_tinh = '<img height="70px" src="img/icon/profile.png">';
            }else{
                $thuoc_tinh = '<img height="70px" src="img/icon/office-building.png">';
            }
            $release = $check = 0;
            foreach ($record->project as $ch){
                $ch->Samsung_status == 1 ? $release ++ : $check++;
            }
            $data_arr[] = array(
                'samsung_attribute' => $thuoc_tinh,
                "samsung_ga_name" => $ga_name,
                "samsung_dev_name" => '<a href="/project?q=dev_samsung&id='.$record->id.'"> <span>'.$record->samsung_dev_name.'</span></a>',
                "samsung_store_name" => $record->samsung_store_name,
                "samsung_email"=>$record->gadev->gmail.'<p style="margin: auto" class="text-muted ">'.$record->samsung_pass .'</p>',
                "project" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "samsung_status"=>$status,
                "samsung_note"=>$record->samsung_note,
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
            'samsung_store_name' =>'unique:ngocphandang_dev_samsung,samsung_store_name',
            'samsung_dev_name' =>'unique:ngocphandang_dev_samsung,samsung_dev_name',
            'samsung_email' =>'required|not_in:0',
        ];
        $message = [
            'samsung_dev_name.unique'=>'Dev name tồn tại',
            'samsung_store_name.unique'=>'Store name đã tồn tại',
            'samsung_email.not_in'=>'Vui lòng chọn Email',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Dev_Samsung();
        $data['samsung_ga_name'] = $request->samsung_ga_name;
        $data['samsung_email'] = $request->samsung_email;
        $data['samsung_dev_name'] = $request->samsung_dev_name;
        $data['samsung_store_name'] = $request->samsung_store_name;
        $data['samsung_phone'] = $request->samsung_phone;
        $data['samsung_profile_info'] = $request->samsung_profile_info;
        $data['samsung_pass'] = $request->samsung_pass;
        $data['samsung_status'] = $request->samsung_status;
        $data['samsung_note'] = $request->samsung_note;
        $data['samsung_company'] = $request->samsung_company;
        $data['samsung_add'] = $request->samsung_add;
        $data['samsung_attribute'] = $request->attribute;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function edit($id)
    {
        $dev = Dev_Samsung::find($id);
        return Response::json($dev);
    }
    public function update(Request $request)
    {

        $id = $request->id;
        $rules = [
            'samsung_store_name' =>'unique:ngocphandang_dev_samsung,samsung_store_name,'.$id.',id',
            'samsung_dev_name' =>'unique:ngocphandang_dev_samsung,samsung_dev_name,'.$id.',id',

            'samsung_email' =>'required|not_in:0',
        ];
        $message = [
            'samsung_dev_name.unique'=>'Dev name tồn tại',
            'samsung_store_name.unique'=>'Store name đã tồn tại',

            'samsung_email.not_in'=>'Vui lòng chọn Email',
        ];


            $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Dev_Samsung::find($id);
        $data->samsung_ga_name = $request->samsung_ga_name;
        $data->samsung_email = $request->samsung_email;
        $data->samsung_dev_name = $request->samsung_dev_name;
        $data->samsung_store_name = $request->samsung_store_name;
        $data->samsung_phone = $request->samsung_phone;
        $data->samsung_profile_info = $request->samsung_profile_info;
        $data->samsung_pass = $request->samsung_pass;
        $data->samsung_status = $request->samsung_status;
        $data->samsung_note = $request->samsung_note;
        $data->samsung_add = $request->samsung_add;
        $data->samsung_company = $request->samsung_company;
        $data->samsung_attribute = $request->attribute;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        Dev_Samsung::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
