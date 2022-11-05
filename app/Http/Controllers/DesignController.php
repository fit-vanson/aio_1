<?php

namespace App\Http\Controllers;

use App\Http\Resources\LangsResource;
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
use function Symfony\Component\String\s;

class DesignController extends Controller
{
    public function index(){

//        $this->encodeUrl();

        $header = [
            'title' => 'Design',
            'button' => [
                'Create'            => ['id'=>'createNewDesign','style'=>'primary'],
            ],
            'badge' => [
//                'Đang phát triển' => ['style'=>'primary'],
                'Có Banner'            => ['style'=>'badge badge-warning'],
                'Có Video'         => ['style'=>'badge badge-info'],
                'Có Banner-Video'    => ['style'=>'badge badge-success'],
                'Chưa có gì'    => ['style'=>'badge badge-danger'],
            ]
        ];
        $lags = Language::all();
        return view('design.index')
        ->with(compact('lags','header'));
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

        // Total records
        $totalRecords = Project::whereHas('lang', function ($query) {
            return $query->where('banner', 1);
            })
            ->select('count(*) as allcount')
            ->count();

        $totalRecordswithFilter = Project::whereHas('lang', function ($query) {
            return $query->where('banner', 1);
            })
            ->select('count(*) as allcount')
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('projectid', 'like', '%' . $searchValue . '%');
            })
            ->Where('status_design', 'like', '%' .$columnName_arr[3]['search']['value'] . '%')
            ->count();

        $records = Project::whereHas('lang', function ($query) {
                return $query->where('banner', 1);
            })
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('projectid', 'like', '%' . $searchValue . '%');
            })
            ->Where('status_design', 'like', '%' . $columnName_arr[3]['search']['value'] . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $key=>$record) {
            $btn = ' <a href="javascript:void(0)"  data-name="'.$record->projectname.'" data-id="'.$record->projectid.'" class="btn btn-warning editProjectLang"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';

            $project_name = $record->projectname;
            $langs = $record->lang;
            $design = '';
            foreach ($langs as $lang){
                $preview = $lang->pivot->preview;
                $ret = array_filter($this->array_slice_assoc($lang->pivot->toArray(), ['banner', 'video']));

                if(array_key_exists('banner',$ret) && array_key_exists('video',$ret)){
                    $result = ' <span style="font-size: 100%" class="badge badge-success">'.$lang->lang_name. ' ('.($preview).') </span> ' ;
                }elseif (array_key_exists('video',$ret)){
                    $result = ' <span style="font-size: 100%" class="badge badge-info">'.$lang->lang_name.' ('.($preview).') </span> ' ;
                }elseif(array_key_exists('banner',$ret) ){
                    $result = ' <span style="font-size: 100%" class="badge badge-warning">'.$lang->lang_name.' ('.($preview).') </span> ' ;
                }else{
                    $result = ' <span style="font-size: 100%" class="badge badge-danger">'.$lang->lang_name.' ('.($preview).') </span> ' ;
                }
                $design .=  $result;
                $banner[$lang->lang_code] =$lang->pivot->banner;
            }


            if($record->da){
                $mada = $record->da->ma_da;
            }

            if(!empty(array_filter($banner))){
                $random_lang = array_rand(array_filter($banner),1);
                $random_banner = '<a class="image-popup-no-margins image" style="margin:5px" href="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/'.$random_lang.'/bn.jpg').'" title="'.$random_lang.' Banner">' .
                    '<img  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/'.$random_lang.'/bn.jpg').'" alt="'.$random_lang.' Banner" height="100">' .
                    '</a>';
            }else{
                $random_banner = '<a class="image-popup-no-margins image" style="margin:5px" href="assets\images\logo-sm.png" title="Logo">' .
                    '<img  src="assets\images\logo-sm.png" alt="logo" height="100">' .
                    '</a>';
            }

            if(isset($record->logo)){
                $logo = '<a class="image-popup-no-margins image" style="margin:5px" href="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg.png').'" title="Logo">' .
                    '<img  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg.png').'" alt="logo" height="100">' .
                    '</a>';
            }else{
                $logo = '<a class="image-popup-no-margins image " style="margin:5px" href="assets\images\logo-sm.png" title="Logo">' .
                    '<img  src="assets\images\logo-sm.png" alt="logo" height="100">' .
                    '</a>';
            }



            $data_arr[] = array(
                'logo' => $logo.$random_banner,
                'projectid' => $project_name,
                'lang_id' => $design,
                'user_design' => $record->user ? $record->user->name : 'Admin',
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


        if(empty($request->project_id)){
            return response()->json(['errors'=> 'Chọn Project']);
        }

//        $du_an = preg_split("/[-]+/",$request->projectname)[0];
//
//        if($request->lang_code != 'undefined'){
//            $path = storage_path('app/public/projects/'.trim($du_an).'/'.trim($request->projectname).'/'.trim($request->lang_code).'/');
//            if (!file_exists($path)) {
//                mkdir($path, 777, true);
//            }
//        }
//        $action = $request->action;
        $project = Project::find($request->project_id);
        $du_an = $project->da->ma_da;
        $langs = Language::all()->toArray();

        $output_langs = [];
        array_walk($langs, function($entry) use (&$output_langs) {
            $output_langs[$entry["id"]] = $entry["lang_code"];
        });





        $path = storage_path('app/public/projects/'.$du_an.'/'.$project->projectname.'/');
        if (!file_exists($path)) {
            mkdir($path, 777, true);
        }

        if($request->logo != 'undefined'){
            $logo = $request->logo;
            $img = Image::make($logo->path());
            $img->resize(512, 512)
                ->save($path.'lg.png',85);
            $img->resize(114, 114)
                ->save($path.'lg114.png',85);
            $project->logo = 'lg.png';
        }

        $market = [];
        foreach ($request->markets as $key=>$value){

            $path_market =  $path.$output_langs[$key].'/';
            if (!file_exists($path_market)) {
                mkdir($path_market, 777, true);
            }
            if($value['banner'] != 'undefined' ){
                $banner = $value['banner'];
                $img = Image::make($banner->path());
                $img->resize(1024, 500)
                    ->save($path_market.'bn.jpg', 85, 'jpg');
                $market[$key]['banner'] = 1;
            }
            if($value['video'] != 'undefined' ){
                $video = $value['video'];
                $video->move($path_market, 'video.mp4');
                $market[$key]['video'] = 1;
            }


            if(isset($value['preview']) && $value['preview'] != 'undefined' ){
                $previews = $value['preview'];
                $num = 1;
                foreach ($previews as $file) {
                    $img = Image::make($file->path());
                    if($img->height() > $img->width()){
                        $img
                            ->resize(1080, 1920)
                            ->save($path_market.'pr'.$num.'.jpg',85);
                    }elseif ($img->height() < $img->width()){
                        $img
                            ->resize(1920, 1080)
                            ->save($path_market.'pr'.$num.'.jpg',85);
                    }else{
                        $img
                            ->resize(1920, 1920)
                            ->save($path_market.'pr'.$num.'.jpg',85);
                    }
                    $market[$key]['preview'] = $num;
                    $num ++;
                }
            }
        }
        $project->user_design = auth()->id();
        if ($project->status_design == 2  ){
            $project->status_design = 1;
        }
        $project->save();
        foreach ($market as $k=>$v){
            $project->lang()->syncWithPivotValues($k, $v,false);
        }



//        dd($request->all());
//
//
//        dd($du_an);
//
//        switch ($action){
//            case 'logo':
//                $path_logo = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
//                if (!file_exists($path_logo)) {
//                    mkdir($path_logo, 777, true);
//                }
//                $files = $request->file('logo');
//                foreach ($files as $file) {
//                    $img = Image::make($file->path());
//                    $img->resize(512, 512)
//                        ->save($path_logo.'lg.png',85);
//                    $img->resize(114, 114)
//                        ->save($path_logo.'lg114.png',85);
//                }
//                $project->logo = 'lg.png';
//                break;
//            case 'banner':
//                $files = $request->file('banner');
//                foreach ($files as $file) {
//                    $img = Image::make($file->path());
//                    $img
//                        ->resize(1024, 500)
//                        ->save($path.'bn.jpg',85);
//                }
//                $project->user_design = auth()->id();
//                $project->lang()->syncWithPivotValues($request->lang, ['banner'=> 1],false);
////                $project->lang()->updateExistingPivot((int)$request->lang, ['banner'=>1]);
//                break;
//            case 'preview':
//                $files = $request->file();
//
//
//
//                foreach ($files as $key=>$file) {
//
//
//                    $img = Image::make($file[0]->path());
//
//
//                    if($img->height() > $img->width()){
//                        $img
//                            ->resize(1080, 1920)
//                            ->save($path.$key.'.jpg',85);
//                    }elseif ($img->height() < $img->width()){
//                        $img
//                            ->resize(1920, 1080)
//                            ->save($path.$key.'.jpg',85);
//                    }else{
//                        $img
//                            ->resize(1920, 1920)
//                            ->save($path.$key.'.jpg',85);
//                    }
//                }
//
//                $project->user_design = auth()->id();
//                $project->lang()->syncWithPivotValues($request->lang, [$key=> 1],false);
////                $project->lang()->updateExistingPivot((int)$request->lang, ['preview'=> $key+1]);
//                break;
//            case 'video':
//                $files = $request->file('video');
//                foreach ($files as $file) {
//                    $file->move($path, 'video.mp4');
//                }
//                $project->user_design = auth()->id();
//                $project->lang()->syncWithPivotValues($request->lang, ['video'=> 1],false);
////                $project->lang()->updateExistingPivot((int)$request->lang, ['video'=> 1]);
//                break;
//        }
//        $project->save();


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


    function encodeUrl(){
        //project_id
        //lang_id
        // option
        $project_id  = 1;
        $lang_id = 1 ;
        $option = 'banner' ;

        $url = ProjectHasLang::where('project_id',$project_id)->where('lang_id',$lang_id)->first();
        dd($url);
        dd(1);
    }

//    public function convert(){
//        $lang_projects = ProjectHasLang::all();
//        foreach ($lang_projects as $project){//
//            $sum= array_sum($this->array_slice_assoc($project->toArray(), ['pr1','pr2','pr3','pr4','pr5','pr6','pr7','pr8']));
//            $project->preview = $sum;
//            $project->save();
//        }
//    }

}
