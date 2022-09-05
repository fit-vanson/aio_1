<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Language;
use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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


//        dd(auth()->user());


        // Total records
        $totalRecords = ProjectModel::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->where('samsung_ga_name', 'like', '%' . $searchValue . '%')

            ->count();

        // Get records, also we have included search filter as well
        $records = Dev_Samsung::orderBy($columnName, $columnSortOrder)
            ->with('gadev','ga','project')
            ->where('samsung_ga_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_dev_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_store_name', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_email', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_status', 'like', '%' . $searchValue . '%')
            ->orWhere('samsung_note', 'like', '%' . $searchValue . '%')
            ->select('ngocphandang_dev_samsung.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editDevSamsung('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevSamsung"><i class="ti-trash"></i></a>';



            if($record->samsung_status == 0){
                $status =  '<span class="badge badge-dark">Chưa xử dụng</span>';
            }
            if($record->samsung_status == 1){
                $status = '<span class="badge badge-primary">Đang phát triển</span>';
            }
            if($record->samsung_status == 2){
                $status = '<span class="badge badge-warning">Đóng</span>';
            }
            if($record->samsung_status == 3){
                $status = '<span class="badge badge-danger">Suspend</span>';
            }


            if($record->samsung_ga_name == 0 ){
                $ga_name =  '<span class="badge badge-dark">Chưa có</span>';
            }else{
                $ga_name = $record->ga->ga_name;
            }

            if($record->samsung_attribute !=0){
                $thuoc_tinh = '<img height="70px" src="img/icon/profile.png">';
            }else{
                $thuoc_tinh = '<img height="70px" src="img/icon/office-building.png">';
            }
            $release = $check = 0;
            foreach ($record->project as $ch){
                $ch->Samsung_status == 1 ? $release ++ : $check++;
            }
            $data_arr[] = array(
                'samsung_attribute' => $thuoc_tinh,
                "samsung_ga_name" => $ga_name,
                "samsung_dev_name" => '<a href="/project?q=dev_samsung&id='.$record->id.'"> <span>'.$record->samsung_dev_name.'</span></a>',
                "samsung_store_name" => $record->samsung_store_name,
                "samsung_email"=>$record->gadev->gmail.'<p style="margin: auto" class="text-muted ">'.$record->samsung_pass .'</p>',
                "project" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "samsung_status"=>$status,
                "samsung_note"=>$record->samsung_note,
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

        $du_an = preg_split("/[-]+/",$request->projectname)[0];

        if($request->lang_code != 'undefined'){
            $path = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/'.$request->lang_code.'/');
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
                break;
            case 'banner':
                $files = $request->file('banner');
                foreach ($files as $file) {
                    $img = Image::make($file->path());
                    $img
                        ->save($path.'bn.'.$file->extension(),80);
                }
                break;
            case 'preview':
                $files = $request->file('preview');
                foreach ($files as $key=>$file) {
                    $img = Image::make($file->path());
                    $img
                        ->save($path.'pr'.($key+1).'.'.$file->extension(),80);
                }
                break;
            case 'video':

                $files = $request->file('video');
                foreach ($files as $file) {
                    $file->move($path, 'video.'.$file->extension());
                }
                break;
        }
        return response()->json(['success'=>'Thành công']);
    }
}
