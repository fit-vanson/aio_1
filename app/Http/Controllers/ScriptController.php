<?php

namespace App\Http\Controllers;

use App\Models\Script;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    public function index()
    {
        return view('script.index');
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
        $totalRecords = Script::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Script::select('count(*) as allcount')
            ->where('name_script', 'like', '%' . $searchValue . '%')
            ->orWhere('script', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Script::orderBy($columnName, $columnSortOrder)
            ->where('name_script', 'like', '%' . $searchValue . '%')
            ->orWhere('script', 'like', '%' . $searchValue . '%')
            ->orWhere('note', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();




        $data_arr = array();

        foreach ($records as $record) {
            $action = ' <a href="javascript:void(0)" onclick="editScript(\''.$record->id.'\')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';

            $action = $action.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteScript"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "name_script" => $record->name_script,
                "script" => $record->script,
                "note" => $record->note,
                "action" => $action,

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
        $data = new Script();
        $data['name_script'] = $request->name_script;
        $data['script'] = $request->script;
        $data['note'] = $request->note;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);

    }

    public function edit($id)
    {
        $data = Script::where('id',$id)->first();
        return response()->json($data);
    }


    public function update(Request $request)
    {
        $id = $request->id;
        $data = Script::where('id',$id)->first();
        $data->name_script = $request->name_script;
        $data->script = $request->script;
        $data->note = $request->note;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function delete($id)
    {
        Script::where('id',$id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
