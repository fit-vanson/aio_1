<?php

namespace App\Http\Controllers;

use App\Models\CategoryTemplate;
use App\Models\DataProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataProfileController extends Controller
{

    public function index(){
        return view('data-profile.index');
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
        $totalRecords = DataProfile::select('count(*) as allcount')->count();
        $totalRecordswithFilter = DataProfile::select('count(*) as allcount')
            ->where('data_name', 'like', '%' . $searchValue . '%')
            ->orwhere('data_file', 'like', '%' . $searchValue . '%')
            ->orwhere('data_note', 'like', '%' . $searchValue . '%')
            ->count();


        // Get records, also we have included search filter as well
        $records = DataProfile::orderBy($columnName, $columnSortOrder)
            ->where('data_name', 'like', '%' . $searchValue . '%')
            ->orwhere('data_file', 'like', '%' . $searchValue . '%')
            ->orwhere('data_note', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editDataprofile('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDataprofile"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id " => $record->id ,
                "data_name" => $record->data_name,

                "data_file" =>  "<a href='/file-manager/dataFile/".$record->data_file."' target='_blank'>$record->data_file</a>",
                "data_note" => $record->data_note,
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
            'data_name' =>'unique:data_profiles,data_name',
        ];
        $message = [
            'data_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new DataProfile();
        $data['data_name'] = $request->data_name;
        $data['data_note'] = $request->data_note ? $request->data_note : '';
        $destinationPathData = public_path('file-manager/dataFile/');
        if (!file_exists($destinationPathData)) {
            mkdir($destinationPathData, 0777, true);
        }
        $filedata = $request->data_file;
        $extension = $filedata->getClientOriginalExtension();
        $data_file= $request->data_name.'.'.$extension;
        $data['data_file'] = $data_file;
        $filedata->move($destinationPathData, $data_file);
        $data->save();

        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);

    }
    public function edit($id)
    {
        $data = DataProfile::find($id);
        return response()->json($data );
    }
    public function update(Request $request){
        $id = $request->data_id;
        $rules = [
            'data_name' =>'unique:data_profiles,data_name,'.$id.',id',

        ];
        $message = [
            'data_name.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = DataProfile::find($id);
        $destinationPath = public_path('file-manager/dataFile/');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if($request->data_file ){
            $path_Remove = public_path('file-manager/dataFile/') . $data->data_file;
            if (file_exists($path_Remove)) {
                unlink($path_Remove);
            }
            $file = $request->data_file;
            $extension = $file->getClientOriginalExtension();
            $data_file= $request->data_name.'.'.$extension;
            $data['data_file'] = $data_file;
            $file->move($destinationPath, $data_file);
        }

        if($data->data_name != $request->data_name){
            $file= pathinfo($destinationPath.$data->data_file);
            rename($destinationPath.$data->data_file, $destinationPath.$request->data_name.'.'.$file['extension']);
            $data['data_file'] = $request->data_name.'.'.$file['extension'];
        }
        $data->data_name = $request->data_name;
        $data->data_note = $request->data_note ? $request->data_note : '';

        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id){
        $data = DataProfile::find($id);
        $path_Remove = public_path('file-manager/dataFile/') . $data->data_file;
        if (file_exists($path_Remove)) {
            unlink($path_Remove);
        }
        $data->delete();
        return response()->json(['success'=>'Xóa thành công.']);

    }

}
