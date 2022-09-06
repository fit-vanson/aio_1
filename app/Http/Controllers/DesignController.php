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
            $totalRecords = ProjectHasLang::select('count(*) as allcount')->count();
            $totalRecordswithFilter = ProjectHasLang::select('count(*) as allcount')
                ->whereRelation('project','projectname','like', '%' . $searchValue . '%')
                ->count();
            $records = ProjectHasLang::orderBy($columnName, $columnSortOrder)
                ->with('lang','project')
                ->whereRelation('project','projectname','like', '%' . $searchValue . '%')
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
        foreach ($records as $record) {

            $btn = '';
            if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name'))){
                $btn = ' <a href="javascript:void(0)" onclick="editProjectLang('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteProjectLang"><i class="ti-trash"></i></a>';
            }

            $project_name = $record->project->projectname;
            $du_an = preg_split("/[-]+/",$project_name)[0];
            $lang = $record->lang;

//            dd($du_an);

            switch ($record->status){
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
            switch ($record->logo){
                case 0:
                    $logo =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                    break;
                case 1:
                    $logo = '<a class="image-popup-no-margins" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg.png">
                                <img class="img-fluid" alt="" src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/lg114.png" width="120">
                            </a>';
                    break;
            }
            switch ($record->banner){
                case 0:
                    $banner =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                    break;
                case 1:
                    $banner = '<a class="image-popup-no-margins" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg">
                                <img class="img-fluid" alt="" src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/bn.jpg" width="120">
                            </a>';
                    break;
            }
            switch ($record->preview){
                case 0:
                    $preview =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                    break;
                default:
                    $preview = '<div class="popup-gallery">';

                    for ($i=1 ; $i<=$record->preview; $i++){
                        $preview .=  '<a class="float-left" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" title="preview '.$i.'">
                                                <div class="img-responsive">
                                                    <img src="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/pr'.$i.'.jpg" alt="" width="120">
                                                </div>
                                            </a>';
                    }
                    $preview .= '</div>';
                    break;
            }
            switch ($record->video){
                case 0:
                    $video =' <span style="font-size: 100%" class="badge badge-danger"><i class="ti-close"></i></span> ' ;
                    break;
                case 1:
                    $video = '<a class="popup-youtube mo-mb-2" href="'.url('/storage/projects').'/'.$du_an.'/'.$project_name.'/'.$lang->lang_code.'/video.mp4">Video</a>';
                    break;
            }
            $data_arr[] = array(
                'id' => $record->id,
                'project_id' => $project_name,

                'lang_id' => $lang->lang_name,
                'user_design' => $record->user->name,
                'logo' => $logo,
                'banner' => $banner,
                'video' => $video,
                'preview' => $preview,
                'status' => $status,
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
                $project = ProjectModel::find($request->projectid);
                $project->logo = 'lg.png';
                $project->save();
                $project->lang()->syncWithPivotValues($project->lang()->get()->pluck('id')->toArray(),['logo' => 1]);
                break;
            case 'banner':
                $files = $request->file('banner');
                foreach ($files as $file) {
                    $img = Image::make($file->path());
                    $img
//                        ->save($path.'bn.'.$file->extension(),80);
                        ->save($path.'bn.jpg',80);
                }
                $result = ProjectHasLang::updateOrCreate(
                    [
                        'project_id' => $request->projectid,
                        'lang_id' => $request->lang,
                    ],
                    [
                        'banner' => 1,
                        'user_design' => auth()->id(),
                    ]
                );

                break;
            case 'preview':
                $files = $request->file('preview');
                foreach ($files as $key=>$file) {
                    $img = Image::make($file->path());
                    $img
//                        ->save($path.'pr'.($key+1).'.'.$file->extension(),80);
                        ->save($path.'pr'.($key+1).'.jpg',80);
                }

                $result = ProjectHasLang::updateOrCreate(
                    [
                        'project_id' => $request->projectid,
                        'lang_id' => $request->lang,
                    ],
                    [
                        'preview' => $key+1,
                        'user_design' => auth()->id(),
                    ]
                );
                break;
            case 'video':
                $files = $request->file('video');
                foreach ($files as $file) {
                    $file->move($path, 'video.mp4');
                }
                $result = ProjectHasLang::updateOrCreate(
                    [
                        'project_id' => $request->projectid,
                        'lang_id' => $request->lang,
                    ],
                    [
                        'video' => 1,
                        'user_design' => auth()->id(),
                    ]
                );
                break;
        }
        if (isset($result) && $result->status == 2  ){
            $result->update(['status'=>1]);
        }

        return response()->json(['success'=>'Thành công']);
    }
}
