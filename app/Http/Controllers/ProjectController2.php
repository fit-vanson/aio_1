<?php

namespace App\Http\Controllers;

use App\Models\Da;
use App\Models\Dev;

use App\Models\Dev_Amazon;
use App\Models\Dev_Oppo;
use App\Models\Dev_Samsung;
use App\Models\Dev_Vivo;
use App\Models\Dev_Xiaomi;
use App\Models\log;
use App\Models\ProjectModel;
use App\Models\ProjectModel2;
use App\Models\Template;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;



class ProjectController2 extends Controller
{
    public function index()
    {
        $da =  Da::latest('id')->get();
        $template =  Template::latest('id')->get();
        $store_name =  Dev::latest('id')->get();
        $store_name_amazon  =  Dev_Amazon::latest('id')->get();
        $store_name_samsung =  Dev_Samsung::latest('id')->get();
        $store_name_xiaomi  =  Dev_Xiaomi::latest('id')->get();
        $store_name_oppo    =  Dev_Oppo::latest('id')->get();
        $store_name_vivo    =  Dev_Vivo::latest('id')->get();
        return view('project.index2',compact([
            'template','da','store_name',
            'store_name_amazon','store_name_samsung',
            'store_name_xiaomi','store_name_oppo','store_name_vivo'
        ]));
    }

    public function indexBuild()
    {
//        $da =  Da::latest('id')->get();
//        $template =  Template::latest('id')->get();
//        $store_name =  Dev::latest('id')->get();
//        $store_name_amazon  =  Dev_Amazon::latest('id')->get();
//        $store_name_samsung =  Dev_Samsung::latest('id')->get();
//        $store_name_xiaomi  =  Dev_Xiaomi::latest('id')->get();
//        $store_name_oppo    =  Dev_Oppo::latest('id')->get();
//        $store_name_vivo    =  Dev_Vivo::latest('id')->get();
        return view('project.indexBuild');
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
        $totalRecords = ProjectModel2::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProjectModel2::select('count(*) as allcount')
            ->where('ma_da', 'like', '%' . $searchValue . '%')
            ->orWhere('projectname', 'like', '%' . $searchValue . '%')
            ->orWhere('title_app', 'like', '%' . $searchValue . '%')
            ->orWhere('template', 'like', '%' . $searchValue . '%')
            ->orWhere('Chplay_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Amazon_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Samsung_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Xiaomi_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Oppo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = ProjectModel2::orderBy($columnName, $columnSortOrder)
            ->where('ma_da', 'like', '%' . $searchValue . '%')
            ->orWhere('projectname', 'like', '%' . $searchValue . '%')
            ->orWhere('title_app', 'like', '%' . $searchValue . '%')
            ->orWhere('template', 'like', '%' . $searchValue . '%')
            ->orWhere('Chplay_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Amazon_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Samsung_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Xiaomi_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Oppo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn. ' <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';

            $ma_da = DB::table('ngocphandang_project2')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project2.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project2')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project2.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3">' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">'.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">'.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }

            if(isset(json_decode($record->Chplay_ads,true)['ads_id'])
                || isset(json_decode($record->Chplay_ads,true)['ads_banner'])
                || isset(json_decode($record->Chplay_ads,true)['ads_inter'])
                || isset(json_decode($record->Chplay_ads,true)['ads_native'])
                || isset(json_decode($record->Chplay_ads,true)['ads_open'])
                || isset(json_decode($record->Chplay_ads,true)['ads_reward'])
            ){
                $package_chplay = '<p style="color:green;line-height:0.5">CH Play: '.$record->Chplay_package.'</p>';
            }else{
                $package_chplay = '<p style="color:red;line-height:0.5">CH Play: '.$record->Chplay_package.'</p>';
            }

            if(isset(json_decode($record->Amazon_ads,true)['ads_id'])
                || isset(json_decode($record->Amazon_ads,true)['ads_banner'])
                || isset(json_decode($record->Amazon_ads,true)['ads_inter'])
                || isset(json_decode($record->Amazon_ads,true)['ads_native'])
                || isset(json_decode($record->Amazon_ads,true)['ads_open'])
                || isset(json_decode($record->Amazon_ads,true)['ads_reward'])
            ){
                $package_amazon = '<p  style="color:green;line-height:0.5">Amazon: '.$record->Amazon_package.'</p>';
            }else{
                $package_amazon = '<p style="color:red;line-height:0.5">Amazon: '.$record->Amazon_package.'</p>';
            }

            if(isset(json_decode($record->Samsung_ads,true)['ads_id'])
                || isset(json_decode($record->Samsung_ads,true)['ads_banner'])
                || isset(json_decode($record->Samsung_ads,true)['ads_inter'])
                || isset(json_decode($record->Samsung_ads,true)['ads_native'])
                || isset(json_decode($record->Samsung_ads,true)['ads_open'])
                || isset(json_decode($record->Samsung_ads,true)['ads_reward'])
            ){
                $package_samsung = '<p style="color:green;line-height:0.5">SamSung: '.$record->Samsung_package.'</p>';
            }else{
                $package_samsung = '<p style="color:red;line-height:0.5">SamSung: '.$record->Samsung_package.'</p>';
            }

            if(isset(json_decode($record->Xiaomi_ads,true)['ads_id'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_banner'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_inter'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_native'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_open'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_reward'])
            ){
                $package_xiaomi = '<p style="color:green;line-height:0.5">Xiaomi: '.$record->Xiaomi_package.'</p>';
            }else{
                $package_xiaomi = '<p style="color:red;line-height:0.5">Xiaomi: '.$record->Xiaomi_package.'</p>';
            }



            if(isset(json_decode($record->Oppo_ads,true)['ads_id'])
                || isset(json_decode($record->Oppo_ads,true)['ads_banner'])
                || isset(json_decode($record->Oppo_ads,true)['ads_inter'])
                || isset(json_decode($record->Oppo_ads,true)['ads_native'])
                || isset(json_decode($record->Oppo_ads,true)['ads_open'])
                || isset(json_decode($record->Oppo_ads,true)['ads_reward'])
            ){
                $package_oppo = '<p style="color:green;line-height:0.5">Oppo: '.$record->Oppo_package.'</p>';
            }else{
                $package_oppo = '<p style="color:red;line-height:0.5">Oppo: '.$record->Oppo_package.'</p>';
            }

            if(isset(json_decode($record->Vivo_ads,true)['ads_id'])
                || isset(json_decode($record->Vivo_ads,true)['ads_banner'])
                || isset(json_decode($record->Vivo_ads,true)['ads_inter'])
                || isset(json_decode($record->Vivo_ads,true)['ads_native'])
                || isset(json_decode($record->Vivo_ads,true)['ads_open'])
                || isset(json_decode($record->Vivo_ads,true)['ads_reward'])
            ){
                $package_vivo = '<p style="color:green;line-height:0.5">Vivo: '.$record->Vivo_package.'</p>';
            }else{
                $package_vivo = '<p style="color:red;line-height:0.5">Vivo: '.$record->Vivo_package.'</p>';
            }

            if ($record['Chplay_status']==0  ) {
                $Chplay_status = 'Mặc định';
            }
            elseif($record['Chplay_status']== 1){
                $Chplay_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Chplay_status']==2){
                $Chplay_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Chplay_status']==3){
                $Chplay_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Chplay_status']==4){
                $Chplay_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Chplay_status']==5){
                $Chplay_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Chplay_status']==6){
                $Chplay_status =  '<span class="badge badge-danger">Check</span>';
            }
            if ($record['Amazon_status']==0  ) {
                $Amazon_status = 'Mặc định';
            }
            elseif($record['Amazon_status']== 1){
                $Amazon_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Amazon_status']==2){
                $Amazon_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Amazon_status']==3){
                $Amazon_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Amazon_status']==4){
                $Amazon_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Amazon_status']==5){
                $Amazon_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Amazon_status']==6){
                $Amazon_status =  '<span class="badge badge-danger">Check</span>';
            }

            if ($record['Samsung_status']==0  ) {
                $Samsung_status = 'Mặc định';
            }
            elseif($record['Samsung_status']== 1){
                $Samsung_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Samsung_status']==2){
                $Samsung_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Samsung_status']==3){
                $Samsung_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Samsung_status']==4){
                $Samsung_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Samsung_status']==5){
                $Samsung_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Samsung_status']==6){
                $Samsung_status =  '<span class="badge badge-danger">Check</span>';
            }

            if ($record['Xiaomi_status']==0  ) {
                $Xiaomi_status = 'Mặc định';
            }
            elseif($record['Xiaomi_status']== 1){
                $Xiaomi_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Xiaomi_status']==2){
                $Xiaomi_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Xiaomi_status']==3){
                $Xiaomi_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Xiaomi_status']==4){
                $Xiaomi_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Xiaomi_status']==5){
                $Xiaomi_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Xiaomi_status']==6){
                $Xiaomi_status =  '<span class="badge badge-danger">Check</span>';
            }


            if ($record['Oppo_status']==0  ) {
                $Oppo_status = 'Mặc định';
            }
            elseif($record['Oppo_status']== 1){
                $Oppo_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Oppo_status']==2){
                $Oppo_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Oppo_status']==3){
                $Oppo_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Oppo_status']==4){
                $Oppo_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Oppo_status']==5){
                $Oppo_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Oppo_status']==6){
                $Oppo_status =  '<span class="badge badge-danger">Check</span>';
            }

            if ($record['Vivo_status']==0  ) {
                $Vivo_status = 'Mặc định';
            }
            elseif($record['Vivo_status']== 1){
                $Vivo_status = '<span class="badge badge-dark">Publish</span>';

            }
            elseif($record['Vivo_status']==2){
                $Vivo_status =  '<span class="badge badge-warning">Suppend</span>';
            }
            elseif($record['Vivo_status']==3){
                $Vivo_status =  '<span class="badge badge-info">UnPublish</span>';
            }
            elseif($record['Vivo_status']==4){
                $Vivo_status =  '<span class="badge badge-primary">Remove</span>';
            }
            elseif($record['Vivo_status']==5){
                $Vivo_status =  '<span class="badge badge-success">Reject</span>';
            }
            elseif($record['Vivo_status']==6){
                $Vivo_status =  '<span class="badge badge-danger">Check</span>';
            }

            $policy = DB::table('ngocphandang_project2')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project2.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();
            if(isset($policy->policy1) || isset($policy->policy2)){
                $policy = ' <a href="javascript:void(0)" onclick="showPolicy('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a>';
            }else{
                $policy = '';
            }
            $status =  $policy.'<br> CH Play: '.$Chplay_status.'<br> Amazon: '.$Amazon_status.'<br> SamSung: '.$Samsung_status.'<br> Xiaomi: '.$Xiaomi_status.'<br> Oppo: '.$Oppo_status.'<br> Vivo: '.$Vivo_status;

            if(isset($record->logo)){
                $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }
            $data_arr[] = array(
                "updated_at" => $record->updated_at,
                "logo" => $logo,
                "ma_da"=>$data_ma_da.$data_template.$data_projectname.$data_title_app,
                "package" => $package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo,
                "status" => $status,
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

    public function getIndexBuild(Request $request)
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
        $totalRecords = ProjectModel2::select('count(*) as allcount')
            ->where(function ($q){
                $q->where('buildinfo_console',1)
                    ->orWhere('buildinfo_console',4);
            })
            ->count();
        $totalRecordswithFilter = ProjectModel2::select('count(*) as allcount')
//            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->where(function ($a) use ($searchValue) {
                $a->where('projectname', 'like', '%' .$searchValue. '%')
                    ->orWhere('title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('Chplay_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Amazon_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Samsung_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Xiaomi_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Oppo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($q){
                $q->where('buildinfo_console',1)
                    ->orWhere('buildinfo_console',4);
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = ProjectModel2::orderBy($columnName, $columnSortOrder)
            ->where(function ($a) use ($searchValue) {
                $a->where('projectname', 'like', '%' .$searchValue. '%')
                    ->orWhere('title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('Chplay_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Amazon_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Samsung_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Xiaomi_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Oppo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%');
            })
            ->where(function ($q){
                $q->where('buildinfo_console',1)
                    ->orWhere('buildinfo_console',4);
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-warning removeProject"><i class="mdi mdi-file-move"></i></a>';

            $ma_da = DB::table('ngocphandang_project2')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project2.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project2')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project2.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3">' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">'.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">'.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }

            if(isset(json_decode($record->Chplay_ads,true)['ads_id'])
                || isset(json_decode($record->Chplay_ads,true)['ads_banner'])
                || isset(json_decode($record->Chplay_ads,true)['ads_inter'])
                || isset(json_decode($record->Chplay_ads,true)['ads_native'])
                || isset(json_decode($record->Chplay_ads,true)['ads_open'])
                || isset(json_decode($record->Chplay_ads,true)['ads_reward'])
            ){
                $package_chplay = '<p style="color:green;line-height:0.5">CH Play: '.$record->Chplay_package.'</p>';
            }else{
                $package_chplay = '<p style="color:red;line-height:0.5">CH Play: '.$record->Chplay_package.'</p>';
            }

            if(isset(json_decode($record->Amazon_ads,true)['ads_id'])
                || isset(json_decode($record->Amazon_ads,true)['ads_banner'])
                || isset(json_decode($record->Amazon_ads,true)['ads_inter'])
                || isset(json_decode($record->Amazon_ads,true)['ads_native'])
                || isset(json_decode($record->Amazon_ads,true)['ads_open'])
                || isset(json_decode($record->Amazon_ads,true)['ads_reward'])
            ){
                $package_amazon = '<p  style="color:green;line-height:0.5">Amazon: '.$record->Amazon_package.'</p>';
            }else{
                $package_amazon = '<p style="color:red;line-height:0.5">Amazon: '.$record->Amazon_package.'</p>';
            }

            if(isset(json_decode($record->Samsung_ads,true)['ads_id'])
                || isset(json_decode($record->Samsung_ads,true)['ads_banner'])
                || isset(json_decode($record->Samsung_ads,true)['ads_inter'])
                || isset(json_decode($record->Samsung_ads,true)['ads_native'])
                || isset(json_decode($record->Samsung_ads,true)['ads_open'])
                || isset(json_decode($record->Samsung_ads,true)['ads_reward'])
            ){
                $package_samsung = '<p style="color:green;line-height:0.5">SamSung: '.$record->Samsung_package.'</p>';
            }else{
                $package_samsung = '<p style="color:red;line-height:0.5">SamSung: '.$record->Samsung_package.'</p>';
            }

            if(isset(json_decode($record->Xiaomi_ads,true)['ads_id'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_banner'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_inter'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_native'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_open'])
                || isset(json_decode($record->Xiaomi_ads,true)['ads_reward'])
            ){
                $package_xiaomi = '<p style="color:green;line-height:0.5">Xiaomi: '.$record->Xiaomi_package.'</p>';
            }else{
                $package_xiaomi = '<p style="color:red;line-height:0.5">Xiaomi: '.$record->Xiaomi_package.'</p>';
            }



            if(isset(json_decode($record->Oppo_ads,true)['ads_id'])
                || isset(json_decode($record->Oppo_ads,true)['ads_banner'])
                || isset(json_decode($record->Oppo_ads,true)['ads_inter'])
                || isset(json_decode($record->Oppo_ads,true)['ads_native'])
                || isset(json_decode($record->Oppo_ads,true)['ads_open'])
                || isset(json_decode($record->Oppo_ads,true)['ads_reward'])
            ){
                $package_oppo = '<p style="color:green;line-height:0.5">Oppo: '.$record->Oppo_package.'</p>';
            }else{
                $package_oppo = '<p style="color:red;line-height:0.5">Oppo: '.$record->Oppo_package.'</p>';
            }

            if(isset(json_decode($record->Vivo_ads,true)['ads_id'])
                || isset(json_decode($record->Vivo_ads,true)['ads_banner'])
                || isset(json_decode($record->Vivo_ads,true)['ads_inter'])
                || isset(json_decode($record->Vivo_ads,true)['ads_native'])
                || isset(json_decode($record->Vivo_ads,true)['ads_open'])
                || isset(json_decode($record->Vivo_ads,true)['ads_reward'])
            ){
                $package_vivo = '<p style="color:green;line-height:0.5">Vivo: '.$record->Vivo_package.'</p>';
            }else{
                $package_vivo = '<p style="color:red;line-height:0.5">Vivo: '.$record->Vivo_package.'</p>';
            }

            if ($record['buildinfo_console']==0  ) {
                $buildinfo_console = 'Trạng thái tĩnh';
            }
            elseif($record['buildinfo_console']== 1){
                $buildinfo_console = '<span class="badge badge-dark">Build App</span>';

            }
            elseif($record['buildinfo_console']==2){
                $buildinfo_console =  '<span class="badge badge-warning">Đang xử lý Build App</span>';
            }
            elseif($record['buildinfo_console']==3){
                $buildinfo_console =  '<span class="badge badge-info">Kết thúc Build App</span>';
            }
            elseif($record['buildinfo_console']==4){
                $buildinfo_console =  '<span class="badge badge-primary">Check Data Project</span>';
            }
            elseif($record['buildinfo_console']==5){
                $buildinfo_console =  '<span class="badge badge-success">Đang xử lý check dữ liệu của Project</span>';
            }
            elseif($record['buildinfo_console']==6){
                $buildinfo_console =  '<span class="badge badge-danger">Kết thúc Check</span>';
            }
//
            if(isset($record->logo)){
                $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }
            if ($record->buildinfo_mess){
                $mess_info = '';
                $buildinfo_mess = $record->buildinfo_mess;
                $buildinfo_mess =  (explode('|',$buildinfo_mess));
                $buildinfo_mess = array_reverse($buildinfo_mess);
                for($i = 0 ; $i < 6 ; $i++){
                    if(isset($buildinfo_mess[$i])){
                        $mess_info .=  $buildinfo_mess[$i].'<br>';
                    }
                }

            }
            $data_arr[] = array(
                "updated_at" => $record->updated_at,
                "logo" => $logo,
                "ma_da"=>$data_ma_da.$data_template.$data_projectname.$data_title_app,
                "package" => $package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo,
                "buildinfo_mess" => $mess_info,
                "buildinfo_console" =>$buildinfo_console,
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
            'projectname' =>'required|unique:ngocphandang_project2,projectname',
            'ma_da' => 'required|not_in:0',
            'template' => 'required|not_in:0',
            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
            'logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
            'ma_da.required'=>'Mã dự án không để trống',
            'template.required'=>'Mã template không để trống',
            'ma_da.not_in'=>'Mã dự án không để trống',
            'template.not_in'=>'Mã template không để trống',
            'title_app.required'=>'Tiêu đề ứng không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'logo.mimes'=>'Logo không đúng định dạng: jpeg, png, jpg, gif, svg.',
            'logo.max'=>'Logo max: 2M.',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $Chplay_ads = [
            'ads_id' => $request->Chplay_ads_id,
            'ads_banner' => $request->Chplay_ads_banner,
            'ads_inter' => $request->Chplay_ads_inter,
            'ads_reward' => $request->Chplay_ads_reward,
            'ads_native' => $request->Chplay_ads_native,
            'ads_open' => $request->Chplay_ads_open
        ];
        $Chplay_ads =  json_encode($Chplay_ads);

        $Amazon_ads = [
            'ads_id' => $request->Amazon_ads_id,
            'ads_banner' => $request->Amazon_ads_banner,
            'ads_inter' => $request->Amazon_ads_inter,
            'ads_reward' => $request->Amazon_ads_reward,
            'ads_native' => $request->Amazon_ads_native,
            'ads_open' => $request->Amazon_ads_open
        ];
        $Amazon_ads =  json_encode($Amazon_ads);

        $Samsung_ads = [
            'ads_id' => $request->Samsung_ads_id,
            'ads_banner' => $request->Samsung_ads_banner,
            'ads_inter' => $request->Samsung_ads_inter,
            'ads_reward' => $request->Samsung_ads_reward,
            'ads_native' => $request->Samsung_ads_native,
            'ads_open' => $request->Samsung_ads_open
        ];
        $Samsung_ads =  json_encode($Samsung_ads);

        $Xiaomi_ads = [
            'ads_id' => $request->Xiaomi_ads_id,
            'ads_banner' => $request->Xiaomi_ads_banner,
            'ads_inter' => $request->Xiaomi_ads_inter,
            'ads_reward' => $request->Xiaomi_ads_reward,
            'ads_native' => $request->Xiaomi_ads_native,
            'ads_open' => $request->Xiaomi_ads_open
        ];
        $Xiaomi_ads =  json_encode($Xiaomi_ads);

        $Oppo_ads = [
            'ads_id' => $request->Oppo_ads_id,
            'ads_banner' => $request->Oppo_ads_banner,
            'ads_inter' => $request->Oppo_ads_inter,
            'ads_reward' => $request->Oppo_ads_reward,
            'ads_native' => $request->Oppo_ads_native,
            'ads_open' => $request->Oppo_ads_open
        ];
        $Oppo_ads =  json_encode($Oppo_ads);

        $Vivo_ads = [
            'ads_id' => $request->Vivo_ads_id,
            'ads_banner' => $request->Vivo_ads_banner,
            'ads_inter' => $request->Vivo_ads_inter,
            'ads_reward' => $request->Vivo_ads_reward,
            'ads_native' => $request->Vivo_ads_native,
            'ads_open' => $request->Vivo_ads_open
        ];
        $Vivo_ads =  json_encode($Vivo_ads);

        $data = new ProjectModel2();
        $data['projectname'] = $request->projectname;
        $data['template'] = $request->template;
        $data['ma_da'] = $request->ma_da;
        $data['title_app'] = $request->title_app;
        $data['buildinfo_app_name_x'] =  $request->buildinfo_app_name_x;
        $data['buildinfo_link_policy_x'] = $request->buildinfo_link_policy_x;
        $data['buildinfo_link_fanpage'] = $request->buildinfo_link_fanpage;
        $data['buildinfo_link_website'] =  $request->buildinfo_link_website;
        $data['buildinfo_link_youtube_x'] = $request->buildinfo_link_youtube_x;
        $data['buildinfo_api_key_x'] = $request->buildinfo_api_key_x;
        $data['buildinfo_console'] = 0;
        $data['buildinfo_vernum' ]= $request->buildinfo_vernum;
        $data['buildinfo_verstr'] = $request->buildinfo_verstr;
        $data['buildinfo_keystore'] = $request->buildinfo_keystore;
        $data['buildinfo_sdk'] = $request->buildinfo_sdk;

        $data['Chplay_package'] = $request->Chplay_package;
        $data['Chplay_buildinfo_store_name_x'] = $request->Chplay_buildinfo_store_name_x;
        $data['Chplay_buildinfo_link_store'] = $request->Chplay_buildinfo_link_store;
        $data['Chplay_buildinfo_email_dev_x'] = $request->Chplay_buildinfo_email_dev_x;
        $data['Chplay_buildinfo_link_app'] = $request->Chplay_buildinfo_link_app;
        $data['Chplay_ads'] = $Chplay_ads;
        $data['Chplay_status'] = $request->Chplay_status;

        $data['Amazon_package'] = $request->Amazon_package;
        $data['Amazon_buildinfo_store_name_x'] = $request->Amazon_buildinfo_store_name_x;
        $data['Amazon_buildinfo_link_store'] = $request->Amazon_buildinfo_link_store;
        $data['Amazon_buildinfo_email_dev_x'] = $request->Amazon_buildinfo_email_dev_x;
        $data['Amazon_buildinfo_link_app'] = $request->Amazon_buildinfo_link_app;
        $data['Amazon_ads'] = $Amazon_ads;
        $data['Amazon_status'] = $request->Amazon_status;

        $data['Samsung_package'] = $request->Samsung_package;
        $data['Samsung_buildinfo_store_name_x'] = $request->Samsung_buildinfo_store_name_x;
        $data['Samsung_buildinfo_link_store'] = $request->Samsung_buildinfo_link_store;
        $data['Samsung_buildinfo_email_dev_x'] = $request->Samsung_buildinfo_email_dev_x;
        $data['Samsung_buildinfo_link_app'] = $request->Samsung_buildinfo_link_app;
        $data['Samsung_ads'] = $Samsung_ads;
        $data['Samsung_status'] = $request->Samsung_status;

        $data['Xiaomi_package'] = $request->Xiaomi_package;
        $data['Xiaomi_buildinfo_store_name_x'] = $request->Xiaomi_buildinfo_store_name_x;
        $data['Xiaomi_buildinfo_link_store'] = $request->Xiaomi_buildinfo_link_store;
        $data['Xiaomi_buildinfo_email_dev_x'] = $request->Xiaomi_buildinfo_email_dev_x;
        $data['Xiaomi_buildinfo_link_app'] = $request->Xiaomi_buildinfo_link_app;
        $data['Xiaomi_ads'] = $Xiaomi_ads;
        $data['Xiaomi_status'] = $request->Xiaomi_status;

        $data['Oppo_package'] = $request->Oppo_package;
        $data['Oppo_buildinfo_store_name_x'] = $request->Oppo_buildinfo_store_name_x;
        $data['Oppo_buildinfo_link_store'] = $request->Oppo_buildinfo_link_store;
        $data['Oppo_buildinfo_email_dev_x'] = $request->Oppo_buildinfo_email_dev_x;
        $data['Oppo_buildinfo_link_app'] = $request->Oppo_buildinfo_link_app;
        $data['Oppo_ads'] = $Oppo_ads;
        $data['Oppo_status'] = $request->Oppo_status;

        $data['Vivo_package'] = $request->Vivo_package;
        $data['Vivo_buildinfo_store_name_x'] = $request->Vivo_buildinfo_store_name_x;
        $data['Vivo_buildinfo_link_store'] = $request->Vivo_buildinfo_link_store;
        $data['Vivo_buildinfo_email_dev_x'] = $request->Vivo_buildinfo_email_dev_x;
        $data['Vivo_buildinfo_link_app'] = $request->Vivo_buildinfo_link_app;
        $data['Vivo_ads'] = $Vivo_ads;
        $data['Vivo_status'] = $request->Vivo_status;

        if(isset($request->logo)){
            $image = $request->file('logo');
            $data['logo'] = 'logo_'.time().'.'.$image->extension();
            $destinationPath = public_path('uploads/project/'.$request->projectname.'/thumbnail/');
            $img = Image::make($image->path());
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 777, true);
            }
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$data['logo']);
            $destinationPath = public_path('uploads/project/'.$request->projectname);
            $image->move($destinationPath, $data['logo']);
        }
        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id)
    {

        $policy = '';
        $policy = '';

        $project = ProjectModel2::where('projectid',$id)->first();
        $policy = Template::select('policy1','policy2')->where('id',$project->template)->first();
        $store_name= Dev::select('store_name')->where('id',$project->Chplay_buildinfo_store_name_x)->first();

        return response()->json([$project,$policy,$store_name]);
    }

    public function update(Request $request)
    {
        $id = $request->project_id;
        $rules = [
            'projectname' =>'unique:ngocphandang_project2,projectname,'.$id.',projectid',
            'ma_da' => 'required',
            'template' => 'required',
            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
            'logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
            'ma_da.required'=>'Mã dự án không để trống',
            'template.required'=>'Mã template không để trống',
            'title_app.required'=>'Tiêu đề ứng không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'logo.mimes'=>'Logo không đúng định dạng: jpeg, png, jpg, gif, svg.',
            'logo.max'=>'Logo max: 2M.',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $Chplay_ads = [
            'ads_id' => $request->Chplay_ads_id,
            'ads_banner' => $request->Chplay_ads_banner,
            'ads_inter' => $request->Chplay_ads_inter,
            'ads_reward' => $request->Chplay_ads_reward,
            'ads_native' => $request->Chplay_ads_native,
            'ads_open' => $request->Chplay_ads_open
        ];
        $Chplay_ads =  json_encode($Chplay_ads);

        $Amazon_ads = [
            'ads_id' => $request->Amazon_ads_id,
            'ads_banner' => $request->Amazon_ads_banner,
            'ads_inter' => $request->Amazon_ads_inter,
            'ads_reward' => $request->Amazon_ads_reward,
            'ads_native' => $request->Amazon_ads_native,
            'ads_open' => $request->Amazon_ads_open
        ];
        $Amazon_ads =  json_encode($Amazon_ads);

        $Samsung_ads = [
            'ads_id' => $request->Samsung_ads_id,
            'ads_banner' => $request->Samsung_ads_banner,
            'ads_inter' => $request->Samsung_ads_inter,
            'ads_reward' => $request->Samsung_ads_reward,
            'ads_native' => $request->Samsung_ads_native,
            'ads_open' => $request->Samsung_ads_open
        ];
        $Samsung_ads =  json_encode($Samsung_ads);

        $Xiaomi_ads = [
            'ads_id' => $request->Xiaomi_ads_id,
            'ads_banner' => $request->Xiaomi_ads_banner,
            'ads_inter' => $request->Xiaomi_ads_inter,
            'ads_reward' => $request->Xiaomi_ads_reward,
            'ads_native' => $request->Xiaomi_ads_native,
            'ads_open' => $request->Xiaomi_ads_open
        ];
        $Xiaomi_ads =  json_encode($Xiaomi_ads);

        $Oppo_ads = [
            'ads_id' => $request->Oppo_ads_id,
            'ads_banner' => $request->Oppo_ads_banner,
            'ads_inter' => $request->Oppo_ads_inter,
            'ads_reward' => $request->Oppo_ads_reward,
            'ads_native' => $request->Oppo_ads_native,
            'ads_open' => $request->Oppo_ads_open
        ];
        $Oppo_ads =  json_encode($Oppo_ads);

        $Vivo_ads = [
            'ads_id' => $request->Vivo_ads_id,
            'ads_banner' => $request->Vivo_ads_banner,
            'ads_inter' => $request->Vivo_ads_inter,
            'ads_reward' => $request->Vivo_ads_reward,
            'ads_native' => $request->Vivo_ads_native,
            'ads_open' => $request->Vivo_ads_open
        ];
        $Vivo_ads =  json_encode($Vivo_ads);

        $data = ProjectModel2::find($id);


        $data->template = $request->template;
        $data->ma_da = $request->ma_da;
        $data->title_app = $request->title_app;
        $data->buildinfo_app_name_x =  $request->buildinfo_app_name_x;
        $data->buildinfo_link_policy_x = $request->buildinfo_link_policy_x;
        $data->buildinfo_link_fanpage = $request->buildinfo_link_fanpage;
        $data->buildinfo_link_website =  $request->buildinfo_link_website;
        $data->buildinfo_link_youtube_x = $request->buildinfo_link_youtube_x;
        $data->buildinfo_api_key_x = $request->buildinfo_api_key_x;
        $data->buildinfo_vernum= $request->buildinfo_vernum;
        $data->buildinfo_verstr = $request->buildinfo_verstr;
        $data->buildinfo_keystore = $request->buildinfo_keystore;
        $data->buildinfo_sdk = $request->buildinfo_sdk;

        $data->Chplay_package = $request->Chplay_package;
        $data->Chplay_buildinfo_store_name_x = $request->Chplay_buildinfo_store_name_x;
        $data->Chplay_buildinfo_link_store = $request->Chplay_buildinfo_link_store;
        $data->Chplay_buildinfo_link_app = $request->Chplay_buildinfo_link_app;
        $data->Chplay_buildinfo_email_dev_x = $request->Chplay_buildinfo_email_dev_x;
        $data->Chplay_ads = $Chplay_ads;
        $data->Chplay_status = $request->Chplay_status;

        $data->Amazon_package = $request->Amazon_package;
        $data->Amazon_buildinfo_store_name_x = $request->Amazon_buildinfo_store_name_x;
        $data->Amazon_buildinfo_link_store = $request->Amazon_buildinfo_link_store;
        $data->Amazon_buildinfo_link_app = $request->Amazon_buildinfo_link_app;
        $data->Amazon_buildinfo_email_dev_x = $request->Amazon_buildinfo_email_dev_x;
        $data->Amazon_ads = $Amazon_ads;
        $data->Amazon_status = $request->Amazon_status;

        $data->Samsung_package = $request->Samsung_package;
        $data->Samsung_buildinfo_store_name_x = $request->Samsung_buildinfo_store_name_x;
        $data->Samsung_buildinfo_link_store = $request->Samsung_buildinfo_link_store;
        $data->Samsung_buildinfo_link_app = $request->Samsung_buildinfo_link_app;
        $data->Samsung_buildinfo_email_dev_x = $request->Samsung_buildinfo_email_dev_x;
        $data->Samsung_ads = $Samsung_ads;
        $data->Samsung_status = $request->Samsung_status;

        $data->Xiaomi_package = $request->Xiaomi_package;
        $data->Xiaomi_buildinfo_store_name_x = $request->Xiaomi_buildinfo_store_name_x;
        $data->Xiaomi_buildinfo_link_store = $request->Xiaomi_buildinfo_link_store;
        $data->Xiaomi_buildinfo_link_app = $request->Xiaomi_buildinfo_link_app;
        $data->Xiaomi_buildinfo_email_dev_x = $request->Xiaomi_buildinfo_email_dev_x;
        $data->Xiaomi_ads = $Xiaomi_ads;
        $data->Xiaomi_status = $request->Xiaomi_status;

        $data->Oppo_package = $request->Oppo_package;
        $data->Oppo_buildinfo_store_name_x = $request->Oppo_buildinfo_store_name_x;
        $data->Oppo_buildinfo_link_store = $request->Oppo_buildinfo_link_store;
        $data->Oppo_buildinfo_link_app = $request->Oppo_buildinfo_link_app;
        $data->Oppo_buildinfo_email_dev_x = $request->Oppo_buildinfo_email_dev_x;
        $data->Oppo_ads = $Oppo_ads;
        $data->Oppo_status = $request->Oppo_status;

        $data->Vivo_package = $request->Vivo_package;
        $data->Vivo_buildinfo_store_name_x = $request->Vivo_buildinfo_store_name_x;
        $data->Vivo_buildinfo_link_store = $request->Vivo_buildinfo_link_store;
        $data->Vivo_buildinfo_link_app = $request->Vivo_buildinfo_link_app;
        $data->Vivo_buildinfo_email_dev_x = $request->Vivo_buildinfo_email_dev_x;
        $data->Vivo_ads = $Vivo_ads;
        $data->Vivo_status = $request->Vivo_status;
        if($data->logo){
            if($data->projectname <> $request->projectname){
                $dir = (public_path('uploads/project/'));
                rename($dir.$data->projectname, $dir.$request->projectname);
            }
        }
        if($request->logo){
            $image = $request->file('logo');
            $data['logo'] = 'logo_'.time().'.'.$image->extension();
            $destinationPath = public_path('uploads/project/'.$request->projectname.'/thumbnail/');
            $img = Image::make($image->path());
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 777, true);
            }
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$data['logo']);
            $destinationPath = public_path('uploads/project/'.$request->projectname);
            $image->move($destinationPath, $data['logo']);
        }
        $data->projectname = $request->projectname;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function updateQuick(Request $request){
        $id = $request->project_id;

        ProjectModel2::updateOrCreate(
            [
                "projectid" => $id,

            ],
            [
                "buildinfo_vernum" => $request->buildinfo_vernum,
                'buildinfo_verstr' => $request->buildinfo_verstr,
                'buildinfo_console' => $request->buildinfo_console,
                'buildinfo_mess' => 'Chờ xử lý',
                'time_mess' => time(),
                'buildinfo_time' =>time(),

            ]);
        return response()->json(['success'=>'Cập nhật thành công']);


    }


    public function delete($id)
    {
        ProjectModel2::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
//        $this->AuthLogin();
        return parent::callAction($method, array_values($parameters));
    }

    public function removeProject($id){

        $project = ProjectModel2::where('projectid',$id)->first();

        ProjectModel2::updateOrCreate(
            [
                "projectid" => $id,
            ],
            [
                'buildinfo_console' => 0,
                'buildinfo_mess' => '',
                'time_mess' =>time(),
                'buildinfo_time' => time(),

            ]);
        $log_mess = log::where('projectname',$project->projectname)->first();
        if($log_mess){
            $mess = $log_mess->buildinfo_mess .'|'.$project->buildinfo_mess;
        }else{
            $mess = $project->buildinfo_mess;
        }
        log::updateOrCreate(
            [
                "projectname" => $project->projectname,
            ],
            [
                'buildinfo_mess' => $mess
            ]
        );
        return response()->json(['success'=>'Cập nhật thành công']);
    }


    function getProject(){
        $projectAll = ProjectModel::all();
        foreach ($projectAll as $project){
            $Chplay_ads = [];
            $projectid = $project->project;
            $projectname = $project->projectname;
            $template = $project->template;
            $ma_da = $project->ma_da;
            $title_app = $project->title_app;
            $buildinfo_app_name_x = $project->buildinfo_app_name_x;
            $buildinfo_link_policy_x = $project->buildinfo_link_policy_x;
            $buildinfo_link_fanpage = $project->buildinfo_link_fanpage;
            $buildinfo_link_website = $project->buildinfo_link_website;
            $buildinfo_link_youtube_x = $project->buildinfo_link_youtube_x;
            $buildinfo_api_key_x = $project->buildinfo_api_key_x;
            $buildinfo_console = $project->buildinfo_console;
            $buildinfo_vernum = $project->buildinfo_vernum;
            $buildinfo_verstr = $project->buildinfo_verstr;
            $buildinfo_keystore = $project->buildinfo_keystore;
            $buildinfo_sdk = $project->buildinfo_sdk;
            $buildinfo_time = $project->buildinfo_time;
            $buildinfo_mess = $project->buildinfo_mess;
            $bot_timecheck = $project->bot_timecheck;
            $time_mess = $project->time_mess;

            $Chplay_package = $project->package;
            $Chplay_buildinfo_store_name_x = $project->buildinfo_store_name_x;
            $Chplay_buildinfo_link_store = $project->buildinfo_link_store;
            $Chplay_buildinfo_email_dev_x = $project->buildinfo_email_dev_x;
            $Chplay_buildinfo_link_app = $project->buildinfo_link_app;
            $Chplay_status = $project->status;
            $Chplay_ads = [
                'ads_id' =>$project->ads_id,
                'ads_banner' =>$project->ads_banner,
                'ads_inter' =>$project->ads_inter,
                'ads_reward' =>$project->ads_reward,
                'ads_native' =>$project->ads_native,
                'ads_open' =>$project->ads_open
            ];
            $Chplay_ads = json_encode($Chplay_ads);


            ProjectModel2::updateOrCreate(
                [
                    "projectid" => $projectid,

                ],
                [
                    "projectname" => $projectname,
                    'template' => $template,
                    'ma_da' => $ma_da,
                    'title_app' => $title_app,
                    'buildinfo_app_name_x' => $buildinfo_app_name_x,
                    'buildinfo_link_policy_x' =>  $buildinfo_link_policy_x,
                    'buildinfo_link_fanpage' =>  $buildinfo_link_fanpage,
                    'buildinfo_link_website' =>  $buildinfo_link_website,
                    'buildinfo_link_youtube_x' =>  $buildinfo_link_youtube_x,
                    'buildinfo_api_key_x' =>  $buildinfo_api_key_x,
                    'buildinfo_console' =>  $buildinfo_console,
                    'buildinfo_vernum' =>  $buildinfo_vernum,
                    "buildinfo_verstr" => $buildinfo_verstr,
                    "buildinfo_keystore" =>  $buildinfo_keystore,
                    "buildinfo_sdk" =>  $buildinfo_sdk,
                    "buildinfo_time" =>  $buildinfo_time,
                    "buildinfo_mess" =>  $buildinfo_mess,
                    "bot_timecheck" =>  $bot_timecheck,
                    "time_mess" =>  $time_mess,

                    "Chplay_package" =>  $Chplay_package,
                    "Chplay_buildinfo_store_name_x" =>  $Chplay_buildinfo_store_name_x,
                    "Chplay_buildinfo_link_store" =>  $Chplay_buildinfo_link_store,
                    "Chplay_buildinfo_email_dev_x" =>  $Chplay_buildinfo_email_dev_x,
                    "Chplay_buildinfo_link_app" =>  $Chplay_buildinfo_link_app,
                    "Chplay_ads" =>  $Chplay_ads,
                    "Chplay_status" =>  $Chplay_status,
                ]);
        }
    }














}
