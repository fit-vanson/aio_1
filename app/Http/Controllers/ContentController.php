<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\ProjectHasLang;
use App\Models\ProjectModel;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(){
        $lags = Language::all();
        return view('content.index')->with(compact('lags'));
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
        $totalRecords = ProjectModel::has('lang')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProjectModel::has('lang')->select('count(*) as allcount')
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->count();
        $records = ProjectModel::has('lang')
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $key=>$record) {
            $btn = ' <a href="javascript:void(0)"  data-id="'.$record->projectid.'" class="btn btn-warning editContent"><i class="ti-pencil-alt"></i></a>';
            $project_name = $record->projectname;
            $langs = $record->lang;
            $title = $description = $summary = '';
            foreach ($langs as $lang){
                if ($lang->pivot->title != null) {
                    $title .= $lang->lang_code.' : '.$lang->pivot->title.'<br><br>';
                }
                if ($lang->pivot->description != null) {
                    $description .= $lang->lang_code.' : '.mb_substr(strip_tags($lang->pivot->description),0,10).'<br><br>';
                }
                if ($lang->pivot->summary != null) {

                    $summary .= $lang->lang_code.' : '.  $lang->pivot->summary.'<br><br>';
                }
            }
            $data_arr[] = array(
                'id' => $record->id,
                'projectid' => $project_name,
                'title' => $title,
                'description' => $description,
                'summary' => $summary,
                "action"=> $btn,
            );
        }

//        dd($data_arr);
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function create(Request $request){

//        dd($request->all());

        if($request->pro_id == null ){
            return response()->json(['errors'=> 'Chọn Project']);
        }
        $project = ProjectModel::find($request->pro_id);
        $content = $request->content;
//        dd($content);
        $project->lang()->sync($content,false);
        $project->save();
        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id)
    {
        $data= ProjectModel::find($id);
        return response()->json($data->load('lang'));
    }
}
