<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Botlog;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index(){
        return view('bot.index');
    }


    public function load_mess($id){
        $data = Botlog::where('idbot',$id)->orderBy('time','desc')->limit(10)->get();
        return $data;
    }

    public function load_ajax(){

        $bot = Bot::query()
            ->with(['messlog'=>function($q){
                $q->orderBy('time', 'desc')
                    ->take(10);
            }])
            ->get()
            ->toArray();
        $botArr = [];
        foreach ($bot as $item){
            $color = 'rgba(219,17,17,0.69)!important;';
            if (count($item['messlog']) > 0){
                $time = time() - $item['messlog'][0]['time'];
                if ($time < 300 ){
                    $color = 'rgba(108,117,125,0.69)!important;';
                }
            }
            $item['color'] = $color;

            $botArr[] = $item;
        }

        return response()->json([
            'data' =>$botArr,
        ]);
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
        $totalRecords = CheckApi::select('count(*) as allcount')->count();
        $totalRecordswithFilter = CheckApi::select('count(*) as allcount')
            ->where('checkapi_name', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = CheckApi::orderBy($columnName, $columnSortOrder)
//            ->select('*',DB::raw("sum( IF(tp_script_1 = '',0,1)  + IF(tp_script_2 = '',0,1)  + IF(tp_script_3 = '',0,1)  + IF(tp_script_4 = '',0,1)  + IF(tp_script_5 = '',0,1)  + IF(tp_script_6 = '',0,1)  + IF(tp_script_7 = '',0,1)  + IF(tp_script_8 = '',0,1)  ) AS sum_script") )
            ->where('checkapi_name', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editCheckAPI('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteCheckAPI"><i class="ti-trash"></i></a>';


            $data_arr[] = array(
                "id " => $record->id ,
                "checkapi_name" => $record->checkapi_name,
                "checkapi_url" => $record->checkapi_url,
                "checkapi_type" => $record->checkapi_type,

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
            'checkapi_name' =>'unique:check_apis,checkapi_name',
        ];
        $message = [
            'checkapi_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new CheckApi();
        $data['checkapi_name'] = $request->checkapi_name;
        $data['checkapi_url'] = $request->checkapi_url;
        $data['checkapi_type'] = $request->checkapi_type;
        $data['checkapi_code'] = $request->checkapi_code;
        $data->save();



        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);

    }
    public function edit($id)
    {
        $temp = CheckApi::find($id);

        return response()->json($temp);
    }
    public function update(Request $request){

        $id = $request->id;

        $rules = [
            'checkapi_name' =>'unique:check_apis,checkapi_name,'.$id.',id',

        ];
        $message = [
            'checkapi_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = CheckApi::find($id);
        $data->checkapi_name = $request->checkapi_name;
        $data->checkapi_url = $request->checkapi_url;
        $data->checkapi_type = $request->checkapi_type;
        $data->checkapi_code = $request->checkapi_code;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id){
        $data = CategoryTemplateFrame::find($id);

        $data->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }
    public function truncate(){
        $data = Botlog::truncate();
        return response()->json(['success'=>'Xóa thành công.']);

    }
}
