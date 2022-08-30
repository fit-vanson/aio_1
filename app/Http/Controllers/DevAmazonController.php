<?php

namespace App\Http\Controllers;

use App\Models\Dev;

use App\Models\Dev_Amazon;
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

class DevAmazonController extends Controller
{
    public function index()
    {
//        dd(1);
        $ga_name = Ga::latest('id')->get();
        $ga_dev = Ga_dev::latest('id')->get();
        $profiles = ProfileV2::orderBy('profile_name','asc')->get();
        return view('dev-amazon.index',compact(['ga_name','ga_dev','profiles']));
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
        $totalRecords = Dev_Amazon::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Dev_Amazon::select('count(*) as allcount')
            ->where('amazon_ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_dev_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_email', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_status', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Dev_Amazon::orderBy($columnName, $columnSortOrder)
            ->with('ga','gadev','project')
            ->where('amazon_ga_name', 'like', '%' . $searchValue . '%')
//            ->where('amazon_dev_name', 'DEV A 10')
            ->orWhere('amazon_dev_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_email', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_status', 'like', '%' . $searchValue . '%')
            ->orWhere('amazon_note', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();



        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editDevAmazon('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevAmazon"><i class="ti-trash"></i></a>';

            if($record->amazon_status == 0){
                $status =  '<span class="badge badge-dark">Chưa xử dụng</span>';
            }
            if($record->amazon_status == 1){
                $status = '<span class="badge badge-primary">Đang phát triển</span>';
            }
            if($record->amazon_status == 2){
                $status = '<span class="badge badge-warning">Đóng</span>';
            }
            if($record->amazon_status == 3){
                $status = '<span class="badge badge-danger">Suspend</span>';
            }
            $release = $check = 0;
            foreach ($record->project as $ch){
                $ch->Amazon_status == 1 ? $release ++ : $check++;
            }
            if($record->amazon_ga_name == 0 ){
                $ga_name =  '<span class="badge badge-dark">Chưa có</span>';
            }else{
                $ga_name = $record->ga->ga_name;
            }
            if($record->amazon_attribute !=0){
                $thuoc_tinh = '<img height="70px" src="img/icon/profile.png">';
            }else{
                $thuoc_tinh = '<img height="70px" src="img/icon/office-building.png">';
            }
            $data_arr[] = array(
                'amazon_attribute' => $thuoc_tinh,
                "amazon_ga_name" => $ga_name,
                "amazon_dev_name" => '<a href="/project?q=dev_amazon&id='.$record->id.'"> <span>'.$record->amazon_dev_name.'</span></a>',
                "project" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "amazon_store_name" => $record->amazon_store_name,
                "amazon_email"=>$record->gadev->gmail . '<p style="margin: auto" class="text-muted ">'.$record->amazon_pass .'</p>',
                "amazon_status"=>$status,
                "amazon_note"=>$record->amazon_note ? substr($record->amazon_note, 0, 20) . '...' : "",
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
            'amazon_store_name' =>'unique:ngocphandang_dev_amazon,amazon_store_name',
            'amazon_dev_name' =>'unique:ngocphandang_dev_amazon,amazon_dev_name',

            'amazon_email' =>'required|not_in:0',
        ];
        $message = [
            'amazon_dev_name.unique'=>'Dev name đã tồn tại',
            'amazon_store_name.unique'=>'Store name tồn tại',

            'amazon_email.not_in'=>'Vui lòng chọn Email',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Dev_Amazon();
        $data['amazon_ga_name'] = $request->amazon_ga_name;
        $data['amazon_email'] = $request->amazon_email;
        $data['amazon_dev_name'] = $request->amazon_dev_name;
        $data['amazon_store_name'] = $request->amazon_store_name;
        $data['amazon_phone'] = $request->amazon_phone;
        $data['amazon_profile_info'] = $request->amazon_profile_info;
        $data['amazon_company'] = $request->amazon_company;
        $data['amazon_add'] = $request->amazon_add;
        $data['amazon_pass'] = $request->amazon_pass;
        $data['amazon_status'] = $request->amazon_status;
        $data['amazon_note'] = $request->amazon_note;
        $data['amazon_attribute'] = $request->attribute;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }
    public function edit($id)
    {
        $dev = Dev_Amazon::find($id);
        return Response::json($dev);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $rules = [
            'amazon_store_name' =>'unique:ngocphandang_dev_amazon,amazon_store_name,'.$id.',id',
            'amazon_dev_name' =>'unique:ngocphandang_dev_amazon,amazon_dev_name,'.$id.',id',
            'amazon_email' =>'required|not_in:0',
        ];
        $message = [
            'amazon_dev_name.unique'=>'Dev name đã tồn tại',
            'amazon_store_name.unique'=>'Store name tồn tại',
            'amazon_email.not_in'=>'Vui lòng chọn Email',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Dev_Amazon::find($id);
        $data->amazon_ga_name = $request->amazon_ga_name;
        $data->amazon_email = $request->amazon_email;
        $data->amazon_dev_name = $request->amazon_dev_name;
        $data->amazon_store_name = $request->amazon_store_name;
        $data->amazon_phone = $request->amazon_phone;
        $data->amazon_profile_info = $request->amazon_profile_info;
        $data->amazon_pass = $request->amazon_pass;
        $data->amazon_status = $request->amazon_status;
        $data->amazon_add = $request->amazon_add;
        $data->amazon_company = $request->amazon_company;
        $data->amazon_note = $request->amazon_note;
        $data->amazon_attribute = $request->attribute;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        Dev_Amazon::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
