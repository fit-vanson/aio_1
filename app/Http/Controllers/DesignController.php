<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectHasLang;
use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class DesignController extends Controller
{
    public function index(){
        $lags = Language::all();
//        dd($lag);
        return view('design.index')->with(compact('lags'));
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
        $searchValue =  $search_arr['value']; // Search value

//        dd($searchValue);

        if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name'))){
            // Total records
            $totalRecords = Project::has('lang')->select('count(*) as allcount')->count();
            $totalRecordswithFilter = Project::has('lang')->select('count(*) as allcount')
                ->where('projectname','like', '%' . $searchValue . '%')
                ->Where('status_design', 'like', '%' .$columnName_arr[2]['search']['value'] . '%')
                ->count();
            $records = Project::has('lang')
                ->where('projectname','like', '%' . $searchValue . '%')
                ->Where('status_design', 'like', '%' . $columnName_arr[2]['search']['value'] . '%')
                ->select('*')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }else{
            $totalRecords = Project::has('lang')->where('user_design',auth()->id())->select('count(*) as allcount')->count();
            $totalRecordswithFilter = Project::has('lang')->select('count(*) as allcount')
                ->where('projectname','like', '%' . $searchValue . '%')
                ->Where('status_design', 'like', '%' .$columnName_arr[2]['search']['value'] . '%')
                ->where('user_design',auth()->id())
                ->count();
            $records = Project::has('lang')
                ->where('projectname','like', '%' . $searchValue . '%')
                ->Where('status_design', 'like', '%' .$columnName_arr[2]['search']['value'] . '%')
                ->where('user_design',auth()->id())
                ->select('*')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        $data_arr = array();
        foreach ($records as $key=>$record) {
//            $btn = ' <a href="javascript:void(0)" data-id_row="'.$key.'"  onclick="editProjectLang('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)"  data-id="'.$record->projectid.'" class="btn btn-warning editProjectLang"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';

//            if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name'))){
//                $btn = $btn.' <a href="javascript:void(0)"  data-id="'.$record->projectid.'" class="btn btn-danger deleteProjectLang"><i class="ti-trash"></i></a>';
//            }

            $project_name = $record->projectname;
//            $du_an = preg_split("/[-]+/",$project_name)[0];
            $langs = $record->lang;
            $design = '';
            foreach ($langs as $lang){
                $preview = $this->array_slice_assoc($lang->pivot->toArray(), ['pr1', 'pr2','pr3','pr4','pr5','pr6','pr7','pr8',]);
                $needle = 0;
                $ret =
                    array_keys(
                        array_filter(
                            $this->array_slice_assoc($lang->pivot->toArray(), ['banner', 'video']), function($var) use ($needle){
                        return strpos($var, $needle) !== false;
                    }));

                if(count($ret) == 2 ){
                    $result = ' <span style="font-size: 100%" class="badge badge-danger">'.$lang->lang_name. ' ('.array_sum($preview).') </span> ' ;
                }elseif ((count($ret) < 2) && (count($ret) > 0 )){
                    $result = ' <span style="font-size: 100%" class="badge badge-warning">'.$lang->lang_name.' ('.array_sum($preview).') </span> ' ;
                }else{
                    $result = ' <span style="font-size: 100%" class="badge badge-success">'.$lang->lang_name.' ('.array_sum($preview).') </span> ' ;
                }
                $design .=  $result;
            }

            $data_arr[] = array(
//                'id' => $record->id,
                'projectid' => $project_name,
                'lang_id' => $design,
                'user_design' => $record->user ? $record->user->name : null,
                'status_design' => $record->status_design,
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

    public function project_show(){
        $searchValue = \request()->q;
        $project = Project::latest()
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->get();
        $result = ProjectResource::collection($project);
        return response()->json($result);
    }

    public function create(Request $request){
        if($request->projectid == 'null' || $request->projectname  == 'null'){
            return response()->json(['errors'=> 'Chọn Project']);
        }

        $du_an = preg_split("/[-]+/",$request->projectname)[0];

        if($request->lang_code != 'undefined'){
            $path = storage_path('app/public/projects/'.trim($du_an).'/'.trim($request->projectname).'/'.trim($request->lang_code).'/');
            if (!file_exists($path)) {
                mkdir($path, 777, true);
            }
        }
        $action = $request->action;
        $project = ProjectModel::find($request->projectid);



        switch ($action){
            case 'logo':
                $path_logo = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
                if (!file_exists($path_logo)) {
                    mkdir($path_logo, 777, true);
                }
                $files = $request->file('logo');
                foreach ($files as $file) {
                    $img = Image::make($file->path());
                    $img->resize(512, 512)
                        ->save($path_logo.'lg.png',85);
                    $img->resize(114, 114)
                        ->save($path_logo.'lg114.png',85);
                }
                $project->logo = 'lg.png';
                break;
            case 'banner':
                $files = $request->file('banner');
                foreach ($files as $file) {
                    $img = Image::make($file->path());
                    $img
                        ->resize(1024, 500)
                        ->save($path.'bn.jpg',85);
                }
                $project->user_design = auth()->id();
                $project->lang()->syncWithPivotValues($request->lang, ['banner'=> 1],false);
//                $project->lang()->updateExistingPivot((int)$request->lang, ['banner'=>1]);
                break;
            case 'preview':
                $files = $request->file();



                foreach ($files as $key=>$file) {


                    $img = Image::make($file[0]->path());


                    if($img->height() > $img->width()){
                        $img
                            ->resize(1080, 1920)
                            ->save($path.$key.'.jpg',85);
                    }elseif ($img->height() < $img->width()){
                        $img
                            ->resize(1920, 1080)
                            ->save($path.$key.'.jpg',85);
                    }else{
                        $img
                            ->resize(1920, 1920)
                            ->save($path.$key.'.jpg',85);
                    }
                }

                $project->user_design = auth()->id();
                $project->lang()->syncWithPivotValues($request->lang, [$key=> 1],false);
//                $project->lang()->updateExistingPivot((int)$request->lang, ['preview'=> $key+1]);
                break;
            case 'video':
                $files = $request->file('video');
                foreach ($files as $file) {
                    $file->move($path, 'video.mp4');
                }
                $project->user_design = auth()->id();
                $project->lang()->syncWithPivotValues($request->lang, ['video'=> 1],false);
//                $project->lang()->updateExistingPivot((int)$request->lang, ['video'=> 1]);
                break;
        }
        $project->save();
        if ($project->status_design == 2  ){
            $project->update(['status_design'=>1]);
        }

        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id)
    {
        $data= Project::find($id);
        return response()->json($data->load('lang','da'));
    }

    public function update(Request $request){
        $data = Project::find($request->design_id_edit);
        $data->notes_design     = $request->notes;
        $data->status_design    = $request->status;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công','data'=>$data]);
    }

    public function delete($id)
    {
        ProjectHasLang::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    function array_slice_assoc($array,$keys) {
        return array_intersect_key($array,array_flip($keys));
    }
}
