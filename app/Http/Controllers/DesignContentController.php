<?php

namespace App\Http\Controllers;

use App\Models\Da;
use App\Models\Project;

use Illuminate\Http\Request;

class DesignContentController extends Controller
{
    public function index(Request $request)
    {

//        $da = Da::has('project')
//            ->with('project')
//            ->whereHas('project', function ($query) {
//                return $query->whereIN('status_design',[0,1]);
//            })
//            ->get();

//        foreach ($da as $item){
//            dd($item);
//        }
//        dd($da);

//        $projects = Project::has('lang')->whereIN('status_design',[0,1])->orderByDesc('projectname')->get();
//        dd($projects);
        return view('design_content.index');
//        return view('design_content.index')->with(compact('projects'));

    }

    public function getIndex(Request $request){
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


        $totalRecords = Project::select('count(*) as allcount')
            ->has('lang')->whereIN('status_design',[0,1])
            ->count();
        $totalRecordswithFilter = Project::select('count(*) as allcount')
            ->has('lang')
            ->whereIN('status_design',[0,1])
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->has('lang')->whereIN('status_design',[0,1])
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "projectname"=>' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" class="showProject">'.$record->projectname.'</a>'

            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "start" => $start,
            "length" => $rowperpage,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function edit($id){
        $project = Project::find($id);
        return response()->json($project->load('lang','da','ma_template'));

    }

    public function update(Request $request){
        $project = Project::find($request->project_id);
        $project->notes_design = $request->notes_design;
        $action = $request->action;
        switch ($action){
            case 1:
                $project->status_design = 4;
                break;
            case 0:
                $project->status_design = 2;
                break;
        }
        $project->save();
        return response()->json(['success'=>'Cập nhật thành công','id'=>$project->projectid]);

    }
}
