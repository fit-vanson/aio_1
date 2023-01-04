<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectHasLang;
use App\Models\ProjectModel;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(){

        $header = [
            'title' => 'Content',

            'button' => [
                'Create'            => ['id'=>'createNewContent','style'=>'primary'],
            ]

        ];
        $lags = Language::all();
        return view('content.index')->with(compact('lags','header'));
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
        $totalRecords = Project::has('lang')->select('count(*) as allcount')->count();
        $totalRecordswithFilter = Project::has('lang')->select('count(*) as allcount')
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->count();
        $records = Project::has('lang')
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $key=>$record) {
            $btn = ' <a href="javascript:void(0)" data-name="'.$record->projectname.'" data-id="'.$record->projectid.'" class="btn btn-warning editContent"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            $project_name = $record->projectname;
            $langs = $record->lang;
            $title = $description = $summary = '';
            $badges = [
                'primary',
                'success',
                'info',
                'warning',
                'danger',
                'dark',
                'secondary',
            ];
            foreach ($langs as $key=>$lang){


//                $a = str_replace('<br />','\\n',$lang->pivot->description);
                $a = preg_replace("/<br\W*?\/>/", "\n", $lang->pivot->description);;
//                echo (strip_tags($a));


//                dd(strip_tags($lang->pivot->description));

                $html = '<span>'.
                    '<p><b>Title app: </b>'.$lang->pivot->title.'</p>'.
                    '<p><b>Summary: </b>'.$lang->pivot->summary.'</p>'.
                    '<p><b>Description: </b>'.strip_tags($a).'</p>'.
                    '</span>';



                if ($lang->pivot->title != null) {
                    $title .= ' <span style="font-size: 100%"  data-toggle="popover" data-placement="right"  data-content="'.$html.'" class="badge badge-'.$badges[$key].'">'.$lang->lang_name. '</span> ' ;
//                    $title .= $lang->lang_code.' : '.$lang->pivot->title.'<br><br>';
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
                'description' => strip_tags($a),
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
//        if($request->pro_id == null ){
//            return response()->json(['errors'=> 'Chọn Project']);
//        }
//        dd($request->all());

        $project = Project::find($request->project_id);
        $content = $request->project_content;
        $project->lang()->sync($content,false);
        $project->save();
        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id)
    {
        $data= Project::find($id);
        return response()->json($data->load('lang'));
    }

    public function convert(){
        $des = ProjectHasLang::paginate(2000);

        $arr=[];
        foreach ($des as $item){
            $a= preg_replace("/<br\W*?\/>/", "\n", $item->description);;
            $arr[] =
                [
                    'id' =>$item->id,
                    'description' => html_entity_decode(strip_tags($a)),
                ];
        }
        $Instance = new ProjectHasLang();
        $index = 'id';
        batch()->update($Instance, $arr, $index);
        $time = $_GET['time'] ?? 2;
        echo '<META http-equiv="refresh" content="'.$time.';URL=' . $des->nextPageUrl() . '">';
    }
}
