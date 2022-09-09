<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Language;
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
        $searchValue = $search_arr['value']; // Search value


        if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name'))){
            // Total records
            $totalRecords = ProjectModel::has('lang')->select('count(*) as allcount')->count();
            $totalRecordswithFilter = ProjectModel::has('lang')->select('count(*) as allcount')
                ->where('projectname','like', '%' . $searchValue . '%')
                ->count();
//            $records = ProjectHasLang::orderBy($columnName, $columnSortOrder)
//                ->with('lang','project')
//                ->whereRelation('project','projectname','like', '%' . $searchValue . '%')
//                ->skip($start)
//                ->take($rowperpage)
//                ->get();




            $records = ProjectModel::has('lang')
//                ->where('wallpaper_name', 'like', '%' . $searchValue . '%')
                ->select('*')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }else{
            $totalRecords = ProjectHasLang::select('count(*) as allcount')
                ->where('user_design',auth()->id())
                ->count();
            $totalRecordswithFilter = ProjectHasLang::select('count(*) as allcount')
                ->where('user_design',auth()->id())
                ->whereRelation('project','projectname','like', '%' . $searchValue . '%')
                ->count();
            $records = ProjectHasLang::orderBy($columnName, $columnSortOrder)
                ->with('lang','project')
                ->whereRelation('project','projectname','like', '%' . $searchValue . '%')
                ->where('user_design',auth()->id())
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        $data_arr = array();
        foreach ($records as $key=>$record) {
//            $btn = ' <a href="javascript:void(0)" data-id_row="'.$key.'"  onclick="editProjectLang('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)"  data-id="'.$record->projectid.'" class="btn btn-warning editProjectLang"><i class="ti-pencil-alt"></i></a>';

//            if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name'))){
//                $btn = $btn.' <a href="javascript:void(0)"  data-id="'.$record->projectid.'" class="btn btn-danger deleteProjectLang"><i class="ti-trash"></i></a>';
//            }

            $project_name = $record->projectname;
//            $du_an = preg_split("/[-]+/",$project_name)[0];
            $langs = $record->lang;

            switch ($record->status_design){
                case 0:
                    $status ='<span style="font-size: 100%" class="badge badge-secondary">Gửi chờ duyệt</span>' ;
                    break;
                case 1:
                    $status = '<span style="font-size: 100%" class="badge badge-info">Đã chỉnh sửa, cần duyệt lại</span>';
                    break;
                case 2:
                    $status = '<span style="font-size: 100%" class="badge badge-warning">Fail, cần chỉnh sửa</span>';
                    break;
                case 3:
                    $status = '<span style="font-size: 100%" class="badge badge-danger">Fail, Project loại khỏi dự án</span>';
                    break;
                case 4:
                    $status = '<span style="font-size: 100%" class="badge badge-success">Done, Kết thúc Project</span>';
                    break;
            }

            $design = '';

            foreach ($langs as $lang){

                //            switch ($record->logo){
//                case 0:
//                    $logo =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
//                    break;
//                case 1:
//                    $logo =
//                        '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg.png" data-sub-html="<h4>'.$project_name.'</h4>lg.png ">
//                            <img src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg114.png" height="120">
//                        </a>';
//                    break;
//            }


                switch ($lang->pivot->banner){
                    case 0:
                        $banner =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                        break;
                    case 1:
                        $banner =' <span style="font-size: 100%" class="badge badge-success"><i class="ti-check"></i></span> ' ;
//                        $banner =
//                            '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg" data-sub-html="<h4>'.$project_name.' ('.$lang->lang_name.')</h4>bn.jpg">
//                                <img src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg" height="120">
//                            </a>';
                        break;
                }


                switch ($lang->pivot->preview){
                    case 0:
                        $preview =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                        break;
                    default:
                        $preview = ' <span style="font-size: 100%" class="badge badge-success"><i class="ti-check"></i></span> ' ;
    //                    for ($i=1 ; $i<=$record->preview; $i++){
    //                        $preview .=
    //                            '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" data-sub-html="<h4>'.$project_name.' ('.$lang->lang_name.')</h4> pr'.$i.'.jpg">
    //                                <img  src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" height="120">
    //                            </a>';
    //                    }
                        break;
                }
                switch ($lang->pivot->video){
                    case 0:
                        $video =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                        break;
                    case 1:
                        $video = ' <span style="font-size: 100%" class="badge badge-success"><i class="ti-check"></i></span> ' ;
    //                    $video = '<a class="popup-youtube mo-mb-2" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/video.mp4">Video</a>';
                        break;
                }
                $design .= $lang->lang_code.':'. $banner.$preview.$video.'<br><br>';
            }


//            switch ($record->logo){
//                case 0:
//                    $logo =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
//                    break;
//                case 1:
//                    $logo =
//                        '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg.png" data-sub-html="<h4>'.$project_name.'</h4>lg.png ">
//                            <img src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg114.png" height="120">
//                        </a>';
//                    break;
//            }
//            switch ($record->banner){
//                case 0:
//                    $banner =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
//                    break;
//                case 1:
//                    $banner =
//                        '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg" data-sub-html="<h4>'.$project_name.' ('.$lang->lang_name.')</h4>bn.jpg">
//                            <img src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg" height="120">
//                        </a>';
//                    break;
//            }
//            switch ($record->preview){
//                case 0:
//                    $preview =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
//                    break;
//                default:
//                    $preview = '';
//                    for ($i=1 ; $i<=$record->preview; $i++){
//                        $preview .=
//                            '<a href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" data-sub-html="<h4>'.$project_name.' ('.$lang->lang_name.')</h4> pr'.$i.'.jpg">
//                                <img  src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" height="120">
//                            </a>';
//                    }
//                    break;
//            }
//            switch ($record->video){
//                case 0:
//                    $video =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
//                    break;
//                case 1:
//                    $video = '<a class="popup-youtube mo-mb-2" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/video.mp4">Video</a>';
//                    break;
//            }


            $data_arr[] = array(
                'id' => $record->id,
                'projectid' => $project_name,
                'lang_id' => $design,
                'user_design' => $record->user ? $record->user->name : null,
                'status_design' => $status,
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
        $project = ProjectModel::latest()
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
        $project->status_design = 0;

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
                        ->save($path_logo.'lg.png',80);
                    $img->resize(114, 114)
                        ->save($path_logo.'lg114.png',80);
                }
                $project->logo = 'lg.png';
                break;
            case 'banner':
                $files = $request->file('banner');
                foreach ($files as $file) {
                    $img = Image::make($file->path());
                    $img
//                        ->save($path.'bn.'.$file->extension(),80);
                        ->save($path.'bn.jpg',80);
                }
                $project->user_design = auth()->id();
                $project->lang()->syncWithPivotValues($request->lang, ['banner'=> 1],false);
//                $project->lang()->updateExistingPivot((int)$request->lang, ['banner'=>1]);
                break;
            case 'preview':
                $files = $request->file('preview');
                foreach ($files as $key=>$file) {
                    $img = Image::make($file->path());
                    $img
//                        ->save($path.'pr'.($key+1).'.'.$file->extension(),80);
                        ->save($path.'pr'.($key+1).'.jpg',80);
                }

                $project->user_design = auth()->id();
                $project->lang()->syncWithPivotValues($request->lang, ['preview'=> $key+1],false);
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

//        if (isset($result) && $result->status == 2  ){
//            $result->update(['status'=>1]);
//        }

        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id)
    {
        $data= ProjectModel::find($id);
        return response()->json($data);
    }

    public function update(Request $request){
        $data = ProjectModel::find($request->design_id_edit);
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
}
