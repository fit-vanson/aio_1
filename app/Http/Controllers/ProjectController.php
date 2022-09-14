<?php

namespace App\Http\Controllers;

use App\Models\Da;
use App\Models\Dev;

use App\Models\Dev_Amazon;
use App\Models\Dev_Huawei;
use App\Models\Dev_Oppo;
use App\Models\Dev_Samsung;
use App\Models\Dev_Vivo;
use App\Models\Dev_Xiaomi;
use App\Models\Keystore;
use App\Models\log;
use App\Models\MakertProject;
use App\Models\ProjectModel;
use App\Models\Template;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use Mavinoo\Batch\Batch;
use function Psy\debug;


class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $da =  Da::latest('id')->get();
        $keystore =  Keystore::latest('id')->get();
        $template =  Template::latest('id')->get();
        $store_name =  Dev::latest('id')->get();
        $store_name_amazon  =  Dev_Amazon::latest('id')->get();
        $store_name_samsung =  Dev_Samsung::latest('id')->get();
        $store_name_xiaomi  =  Dev_Xiaomi::latest('id')->get();
        $store_name_oppo    =  Dev_Oppo::latest('id')->get();
        $store_name_vivo    =  Dev_Vivo::latest('id')->get();
        $store_name_huawei   =  Dev_Huawei::latest('id')->get();
        return view('project.index',compact([
            'template','da','store_name','keystore',
            'store_name_amazon','store_name_samsung',
            'store_name_xiaomi','store_name_oppo','store_name_vivo','store_name_huawei'
        ]));
    }
    public function indexBuild()
    {
        return view('project.indexBuild');
    }
    public function appAmazon()
    {
        return view('project.appAmazon');
    }
    public function appSamsung()
    {
        return view('project.appSamsung');
    }
    public function appXiaomi()
    {
        return view('project.appXiaomi');
    }
    public function appOppo()
    {
        return view('project.appOppo');
    }
    public function appVivo()
    {
        return view('project.appVivo');
    }
    public function appHuawei()
    {
        return view('project.appHuawei');
    }
    public function appChplay()
    {
        return view('project.appChplay');
    }
    public function getIndex(Request $request)
    {
        if(Auth::user()->part_time != 0 ){
            $this->getIndex_partTime($request);

        }else{
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
            if(isset($request->q)){
                $q = $request->q;
                if($q == 'ma_da'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('ma_da',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.ma_da',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.ma_da',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();
                }
                elseif ($q =='template'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('template',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.template',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.template',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();
                }
                elseif ($q == 'dev_chplay'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Chplay_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Chplay_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Chplay_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_amazon'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Amazon_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Amazon_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Amazon_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_samsung'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Samsung_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Samsung_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Samsung_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_xiaomi'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Xiaomi_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Xiaomi_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Xiaomi_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_oppo'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Oppo_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Oppo_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Oppo_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_vivo'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Vivo_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Vivo_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Vivo_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

                }
                elseif ($q == 'dev_huawei'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')->where('Huawei_buildinfo_store_name_x',$request->id)->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('ngocphandang_project.Huawei_buildinfo_store_name_x',$request->id)
                        ->count();

                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('ngocphandang_project.Huawei_buildinfo_store_name_x',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();
                }
                elseif ($q == 'key_store'){
                    $totalRecords = ProjectModel::select('count(*) as allcount')
                        ->where('buildinfo_keystore',$request->id)
                        ->orWhere('Chplay_keystore_profile',$request->id)
                        ->orWhere('Amazon_keystore_profile',$request->id)
                        ->orWhere('Samsung_keystore_profile',$request->id)
                        ->orWhere('Xiaomi_keystore_profile',$request->id)
                        ->orWhere('Oppo_keystore_profile',$request->id)
                        ->orWhere('Vivo_keystore_profile',$request->id)
                        ->orWhere('Huawei_keystore_profile',$request->id)
                        ->count();
                    $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                        ->where('buildinfo_keystore',$request->id)
                        ->orWhere('Chplay_keystore_profile',$request->id)
                        ->orWhere('Amazon_keystore_profile',$request->id)
                        ->orWhere('Samsung_keystore_profile',$request->id)
                        ->orWhere('Xiaomi_keystore_profile',$request->id)
                        ->orWhere('Oppo_keystore_profile',$request->id)
                        ->orWhere('Vivo_keystore_profile',$request->id)
                        ->orWhere('Huawei_keystore_profile',$request->id)
                        ->count();


                    // Get records, also we have included search filter as well
                    $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                        ->where('buildinfo_keystore',$request->id)
                        ->orWhere('Chplay_keystore_profile',$request->id)
                        ->orWhere('Amazon_keystore_profile',$request->id)
                        ->orWhere('Samsung_keystore_profile',$request->id)
                        ->orWhere('Xiaomi_keystore_profile',$request->id)
                        ->orWhere('Oppo_keystore_profile',$request->id)
                        ->orWhere('Vivo_keystore_profile',$request->id)
                        ->orWhere('Huawei_keystore_profile',$request->id)
                        ->select('ngocphandang_project.*')
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();
                }
            }else{
                $totalRecords = ProjectModel::select('count(*) as allcount')->count();
                $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                    ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                    ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                    ->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Chplay_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_keystore_profile', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Chplay_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Amazon_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Samsung_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Oppo_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Vivo_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Huawei_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_open', 'like', '%' . $searchValue . '%')
                    ->count();

                // Get records, also we have included search filter as well
                $records = ProjectModel::with('log','dev_chplay.ga','dev_amazon.ga','dev_samsung.ga','dev_xiaomi.ga','dev_oppo.ga','dev_vivo.ga','dev_huawei.ga')
                    ->orderBy($columnName, $columnSortOrder)
                    ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                    ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

                    ->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_banner', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Chplay_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_keystore_profile', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_keystore_profile', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Chplay_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Amazon_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Samsung_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Oppo_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Vivo_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Vivo_ads->ads_open', 'like', '%' . $searchValue . '%')

                    ->orWhere('ngocphandang_project.Huawei_ads->ads_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_banner', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_inter', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_reward', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_native', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Huawei_ads->ads_open', 'like', '%' . $searchValue . '%')
                    ->orwhereRelation('dev_chplay','dev_name',  $searchValue )
                    ->orwhereRelation('dev_amazon','amazon_dev_name',  $searchValue )
                    ->orwhereRelation('dev_samsung','samsung_dev_name',  $searchValue )
                    ->orwhereRelation('dev_xiaomi','xiaomi_dev_name',  $searchValue )
                    ->orwhereRelation('dev_oppo','oppo_dev_name',  $searchValue )
                    ->orwhereRelation('dev_vivo','vivo_dev_name',  $searchValue )
                    ->orwhereRelation('dev_huawei','huawei_dev_name',  $searchValue )
//                ->where('ngocphandang_project.projectname', '=','DA136-99')
//                ->select('ngocphandang_project.*')
                    ->skip($start)
                    ->latest('ngocphandang_project.created_at')
                    ->take($rowperpage)
                    ->get();

//            dd($records);
            }

            // Total records
            $data_arr = array();
            foreach ($records as $record) {
                $full_mess ='';
                $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
                if($record->buildinfo_console == 0){
                    $btn = $btn. '   <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
                }
                $btn = $btn.'<br><br> <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'"  class="btn btn-info fakeProject"><i class="ti-info-alt"></i></a>';
                if(isset($record->log)){
//                $btn .= '   <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Log" class="btn btn-secondary showLog_Project"><i class="mdi mdi-file"></i></a>';
                    $btn .= '   <a href="../../project/showlog/'.$record->projectid.'" data-toggle="tooltip" class="btn btn-secondary"><i class="mdi mdi-file"></i></a>';
                    $log = $record->log->buildinfo_mess;
                    $full_mess =  (str_replace('|','<br>',$log));
                }
                if($record->dev_chplay){
                    if($record->dev_chplay->ga){
                        $ga_name_chplay = $record->dev_chplay->ga->ga_name;
                    }else{
                        $ga_name_chplay = '';
                    }
                    $dev_name_chplay = $record->dev_chplay->dev_name;
                }else{
                    $dev_name_chplay = '';
                    $ga_name_chplay = '';
                }
                if($record->dev_amazon){
                    if($record->dev_amazon->ga){
                        $ga_name_amazon = $record->dev_amazon->ga->ga_name;
                    }else{
                        $ga_name_amazon = '';
                    }
                    $dev_name_amazon = $record->dev_amazon->amazon_dev_name;
                }else{
                    $dev_name_amazon = '';
                    $ga_name_amazon = '';
                }
                if($record->dev_samsung ){
                    if($record->dev_samsung->ga){
                        $ga_name_samsung = $record->dev_samsung->ga->ga_name;
                    }else{
                        $ga_name_samsung = '';
                    }
                    $dev_name_samsung = $record->dev_samsung->samsung_dev_name;
                }else{
                    $dev_name_samsung = '';
                    $ga_name_samsung = '';
                }

                if($record->dev_xiaomi){
                    if($record->dev_xiaomi->ga){
                        $ga_name_xiaomi = $record->dev_xiaomi->ga->ga_name;
                    }else{
                        $ga_name_xiaomi = '';
                    }
                    $dev_name_xiaomi = $record->dev_xiaomi->xiaomi_dev_name;
                }else{
                    $dev_name_xiaomi = '';
                    $ga_name_xiaomi = '';
                }

                if($record->dev_oppo){
                    if($record->dev_oppo->ga){
                        $ga_name_oppo = $record->dev_oppo->ga->ga_name;
                    }else{
                        $ga_name_oppo = '';
                    }
                    $dev_name_oppo = $record->dev_oppo->oppo_dev_name;
                }else{
                    $dev_name_oppo = '';
                    $ga_name_oppo = '';
                }

                if($record->dev_vivo){
                    if($record->dev_vivo->ga){
                        $ga_name_vivo = $record->dev_vivo->ga->ga_name;
                    }else{
                        $ga_name_vivo = '';
                    }
                    $dev_name_vivo = $record->dev_vivo->vivo_dev_name;
                }else{
                    $dev_name_vivo = '';
                    $ga_name_vivo = '';
                }

                if($record->dev_huawei){
                    if($record->dev_huawei->ga){
                        $ga_name_huawei = $record->dev_huawei->ga->ga_name;
                    }else{
                        $ga_name_huawei = '';
                    }
                    $dev_name_huawei = $record->dev_huawei->huawei_dev_name;
                }else{
                    $dev_name_huawei = '';
                    $ga_name_huawei = '';
                }

                if (isset($record->link_store_vietmmo)){
                    $data_template =  '<a href="'.$record->link_store_vietmmo.'" target="_blank" ><span class="text-muted" style="line-height:0.5"> ('.$record->template.') </span></a>';
                }else{
                    $data_template =  '<span class="text-muted" style="line-height:0.5"> ('.$record->template.') </span>';
                }

                if(isset($record->projectname)) {
                    $data_projectname =   '<span style="line-height:3">Mã Project: ' . $record->projectname . '</span>';
                }else{
                    $data_projectname='';
                }
                if(isset($record->buildinfo_app_name_x)) {
                    $buildinfo_app_name_x =   '<p class="text-muted"  style="line-height:0.5; ">App Name: '.$record->buildinfo_app_name_x.'</p>';
                }else{
                    $buildinfo_app_name_x='';
                }

                if(isset($record->title_app)) {
                    $data_title_app=  '<span style="line-height:0.5; font-weight: bold">'.$record->title_app.'</span>';
                }else{
                    $data_title_app='';
                }

                if ($record['Chplay_status']==0  ) {
                    $Chplay_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Chplay_status']== 1){
                    $Chplay_status = '<span class="badge badge-success">Publish</span>';
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
                    $Chplay_status =  '<span class="badge badge-dark">Reject</span>';
                }
                elseif($record['Chplay_status']==6){
                    $Chplay_status =  '<span class="badge badge-danger">Check</span>';
                }
                elseif($record['Chplay_status']==7){
                    $Chplay_status =  '<span class="badge badge-warning">Pending</span>';
                }
                if ($record['Amazon_status']==0  ) {
                    $Amazon_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Amazon_status']== 1){
                    $Amazon_status = '<span class="badge badge-success">Publish</span>';

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
                    $Amazon_status =  '<span class="badge badge-dark">Reject</span>';
                }
                elseif($record['Amazon_status']==6){
                    $Amazon_status =  '<span class="badge badge-danger">Check</span>';
                }

                if ($record['Samsung_status']==0  ) {
                    $Samsung_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Samsung_status']== 1){
                    $Samsung_status = '<span class="badge badge-success">Publish</span>';

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
                    $Samsung_status =  '<span class="badge badge-dark">Reject</span>';
                }
                elseif($record['Samsung_status']==6){
                    $Samsung_status =  '<span class="badge badge-danger">Check</span>';
                }

                if ($record['Xiaomi_status']==0  ) {
                    $Xiaomi_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Xiaomi_status']== 1){
                    $Xiaomi_status = '<span class="badge badge-success">Publish</span>';

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
                    $Xiaomi_status =  '<span class="badge badge-dark">Reject</span>';
                }
                elseif($record['Xiaomi_status']==6){
                    $Xiaomi_status =  '<span class="badge badge-danger">Check</span>';
                }


                if ($record['Oppo_status']==0  ) {
                    $Oppo_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Oppo_status']== 1){
                    $Oppo_status = '<span class="badge badge-success">Publish</span>';

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
                    $Oppo_status =  '<span class="badge badge-dark">Reject</span>';
                }
                elseif($record['Oppo_status']==6){
                    $Oppo_status =  '<span class="badge badge-danger">Check</span>';
                }

                if ($record['Vivo_status']==100  ) {
                    $Vivo_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Vivo_status']== 0){
                    $Vivo_status = '<span class="badge badge-warning">UnPublished</span>';
                }
                elseif($record['Vivo_status']==1){
                    $Vivo_status =  '<span class="badge badge-success">Published</span>';
                }
                elseif($record['Vivo_status']==2){
                    $Vivo_status =  '<span class="badge badge-danger">Removed</span>';
                }
                elseif($record['Vivo_status']==3){
                    $Vivo_status =  '<span class="badge badge-primary">To be published</span>';
                }


                if ($record['Huawei_status']==100  ) {
                    $Huawei_status = '<span class="badge badge-secondary">Mặc định</span>';
                }
                elseif($record['Huawei_status']== 0){
                    $Huawei_status = '<span class="badge badge-success">Released</span>';
                }
                elseif($record['Huawei_status']== 1){
                    $Huawei_status = '<span class="badge badge-danger">Release Rejected</span>';
                }
                elseif($record['Huawei_status']==2){
                    $Huawei_status =  '<span class="badge badge-warning">Removed (including forcible removal)</span>';
                }
                elseif($record['Huawei_status']==3){
                    $Huawei_status =  '<span class="badge badge-success">Releasing</span>';
                }
                elseif($record['Huawei_status']==4){
                    $Huawei_status =  '<span class="badge badge-warning">Reviewing</span>';
                }
                elseif($record['Huawei_status']==5){
                    $Huawei_status =  '<span class="badge badge-warning">Updating</span>';
                }
                elseif($record['Huawei_status']==6){
                    $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
                }
                elseif($record['Huawei_status']==7){
                    $Huawei_status =  '<span class="badge badge-warning">Draft</span>';
                }
                elseif($record['Huawei_status']==8){
                    $Huawei_status =  '<span class="badge badge-warning">Update rejected</span>';
                }
                elseif($record['Huawei_status']==9){
                    $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
                }
                elseif($record['Huawei_status']==10){
                    $Huawei_status =  '<span class="badge badge-danger">Removed by developer</span>';
                }
                elseif($record['Huawei_status']==11){
                    $Huawei_status =  '<span class="badge badge-danger">Release canceled</span>';
                }


                if(isset($record->Chplay_policy)){
                    $Chplay_policy = "<a href='$record->Chplay_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Chplay_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }
                if(isset($record->Amazon_policy)){
                    $Amazon_policy = "<a href='$record->Amazon_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Amazon_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }
                if(isset($record->Samsung_policy)){
                    $Samsung_policy = "<a href='$record->Samsung_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Samsung_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }
                if(isset($record->Xiaomi_policy)){
                    $Xiaomi_policy = "<a href='$record->Xiaomi_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Xiaomi_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }
                if(isset($record->Oppo_policy)){
                    $Oppo_policy = "<a href='$record->Oppo_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Oppo_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }
                if(isset($record->Vivo_policy)){
                    $Vivo_policy = "<a href='$record->Vivo_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Vivo_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }

                if(isset($record->Huawei_policy)){
                    $Huawei_policy = "<a href='$record->Huawei_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
                }else{
                    $Huawei_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
                }

                if(isset($record->policy1) || isset($record->policy2)){
                    $policy_chplay = ' <a href="javascript:void(0)" onclick="showPolicy_Chplay('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_amazon = ' <a href="javascript:void(0)" onclick="showPolicy_Amazon('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_xiaomi = ' <a href="javascript:void(0)" onclick="showPolicy_Xiaomi('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_samsung = ' <a href="javascript:void(0)" onclick="showPolicy_Samsung('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_oppo = ' <a href="javascript:void(0)" onclick="showPolicy_Oppo('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_vivo = ' <a href="javascript:void(0)" onclick="showPolicy_Vivo('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                    $policy_huawei = ' <a href="javascript:void(0)" onclick="showPolicy_Huawei('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                }else{
                    $policy_chplay = $policy_amazon = $policy_xiaomi = $policy_samsung = $policy_oppo = $policy_vivo = $policy_huawei = "";
                }
                if($record->Chplay_package){
                    if(isset(json_decode($record->Chplay_ads,true)['ads_id'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_banner'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_inter'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_native'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_open'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_reward'])
                        || isset(json_decode($record->Chplay_ads,true)['ads_start'])
                    ){
                        if($record->Chplay_buildinfo_link_app){
                            $package_chplay = '<a href="'.$record->Chplay_buildinfo_link_app.'" target="_blank"> <span id="package_chplay" style="color:green;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_chplay = '<span id="package_chplay" style="color:orange;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else{
                        if($record->Chplay_buildinfo_link_app){
                            $package_chplay = '<a href="'.$record->Chplay_buildinfo_link_app.'" target="_blank"> <span style="color:red;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span></a> <button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_chplay = '<span style="color:black;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span> <button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }
                    $statusChplay  =  '<span style="line-height:2.5">'.$Chplay_policy.'&nbsp;<img src="img/icon/google.png"> '.$policy_chplay.' '.$Chplay_status.' '. '<span class="badge badge-info">'.$dev_name_chplay.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_chplay.'</span></span>';
                }else{
                    $package_chplay = $statusChplay = '';
                }

                if($record->Amazon_package){
                    if(isset(json_decode($record->Amazon_ads,true)['ads_id'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_banner'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_inter'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_native'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_open'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_reward'])
                        || isset(json_decode($record->Amazon_ads,true)['ads_start'])
                    ){
                        if($record->Amazon_buildinfo_link_app){
                            $package_amazon = '<a href="'.$record->Amazon_buildinfo_link_app.'" target="_blank"><span  style="color:green;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_amazon = '<span style="color:orange;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else{

                        if($record->Amazon_buildinfo_link_app){
                            $package_amazon = '<a href="'.$record->Amazon_buildinfo_link_app.'" target="_blank"><span  style="color:red;line-height:0.5"><img src="img/icon/amazon.png">'.$record->Amazon_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_amazon = '<span style="color:black;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }

                    $statusAmazon = '<br>'.'<span style="line-height:2.5">'.$Amazon_policy.'&nbsp;<img src="img/icon/amazon.png"> '.$policy_amazon.' '.$Amazon_status.' '. '<span class="badge badge-info">'.$dev_name_amazon.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_amazon.'</span></span>';
                }else{
                    $package_amazon = $statusAmazon= '';
                }

                if($record->Samsung_package){
                    if(isset(json_decode($record->Samsung_ads,true)['ads_id'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_banner'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_inter'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_native'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_open'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_reward'])
                        || isset(json_decode($record->Samsung_ads,true)['ads_start'])
                    ){
                        if($record->Samsung_buildinfo_link_app){
                            $package_samsung = '<a href="'.$record->Samsung_buildinfo_link_app.'" target="_blank"><span  style="color:green;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_samsung = '<span style="color:orange;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else{
                        if($record->Samsung_buildinfo_link_app){
                            $package_samsung = '<a href="'.$record->Samsung_buildinfo_link_app.'" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_samsung = '<span style="color:black;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }
                    $statusSamsung =  '<br>'.'<span style="line-height:2.5">'.$Samsung_policy.'&nbsp;<img src="img/icon/samsung.png"> '.$policy_samsung.' '.$Samsung_status.' '. '<span class="badge badge-info">'.$dev_name_samsung.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_samsung.'</span></span>';
                }else{
                    $package_samsung = $statusSamsung = '';
                }

                if ($record->Xiaomi_package){
                    if(isset(json_decode($record->Xiaomi_ads,true)['ads_id'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_banner'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_inter'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_native'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_open'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_reward'])
                        || isset(json_decode($record->Xiaomi_ads,true)['ads_start'])
                    ){
                        if($record->Xiaomi_buildinfo_link_app){
                            $package_xiaomi = '<a href="'.$record->Xiaomi_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_xiaomi = '<span style="color:orange;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else {
                        if ($record->Xiaomi_buildinfo_link_app) {
                            $package_xiaomi = '<a href="' . $record->Xiaomi_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/xiaomi.png"> ' . $record->Xiaomi_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        } else {
                            $package_xiaomi = '<span style="color:black;line-height:0.5"><img src="img/icon/xiaomi.png"> ' . $record->Xiaomi_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }
                    $statusXiaomi =  '<br>'.'<span style="line-height:2.5">'.$Xiaomi_policy.'&nbsp;<img src="img/icon/xiaomi.png"> '.$policy_xiaomi.' '.$Xiaomi_status.' '. '<span class="badge badge-info">'.$dev_name_xiaomi.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_xiaomi.'</span></span>';
                }else{
                    $package_xiaomi = $statusXiaomi = '';
                }

                if($record->Oppo_package){
                    if(isset(json_decode($record->Oppo_ads,true)['ads_id'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_banner'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_inter'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_native'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_open'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_reward'])
                        || isset(json_decode($record->Oppo_ads,true)['ads_start'])
                    ){
                        if($record->Oppo_buildinfo_link_app){
                            $package_oppo = '<a href="'.$record->Oppo_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_oppo = '<span style="color:orange;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else {
                        if ($record->Oppo_buildinfo_link_app) {
                            $package_oppo = '<a href="' . $record->Oppo_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/oppo.png"> ' . $record->Oppo_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        } else {
                            $package_oppo = '<span style="color:black;line-height:0.5"><img src="img/icon/oppo.png"> ' . $record->Oppo_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }
                    $statusOppo = '<br>'.'<span style="line-height:2.5">'.$Oppo_policy.'&nbsp;<img src="img/icon/oppo.png"> '.$policy_oppo.' '.$Oppo_status.' '. '<span class="badge badge-info">'.$dev_name_oppo.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_oppo.'</span></span>';
                }else{
                    $package_oppo = $statusOppo = '';
                }

                if($record->Vivo_package){
                    if(isset(json_decode($record->Vivo_ads,true)['ads_id'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_banner'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_inter'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_native'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_open'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_reward'])
                        || isset(json_decode($record->Vivo_ads,true)['ads_start'])
                    ){
                        if($record->Vivo_buildinfo_link_app){
                            $package_vivo = '<a href="'.$record->Vivo_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_vivo = '<span style="color:orange;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else{
                        if ($record->Vivo_buildinfo_link_app) {
                            $package_vivo = '<a href="' . $record->Vivo_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/vivo.png"> ' . $record->Vivo_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        } else {
                            $package_vivo = '<span style="color:black;line-height:0.5"><img src="img/icon/vivo.png"> ' . $record->Vivo_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }

                    $statusVivo =  '<br>'.'<span style="line-height:2.5">'.$Vivo_policy.'&nbsp;<img src="img/icon/vivo.png"> '.$policy_vivo.' '.$Vivo_status.' '. '<span class="badge badge-info">'.$dev_name_vivo.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_vivo.'</span></span>';
                }else{
                    $package_vivo =  $statusVivo = '';
                }

                if($record->Huawei_package){
                    if(isset(json_decode($record->Huawei_ads,true)['ads_id'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_banner'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_inter'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_native'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_open'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_reward'])
                        || isset(json_decode($record->Huawei_ads,true)['ads_start'])
                    ){
                        if($record->Huawei_buildinfo_link_app){
                            $package_Huawei = '<a href="'.$record->Huawei_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/huawei.png"> '.$record->Huawei_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }else{
                            $package_Huawei = '<span style="color:orange;line-height:0.5"><img src="img/icon/huawei.png"> ' .$record->Huawei_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }else{
                        if ($record->Huawei_buildinfo_link_app) {
                            $package_Huawei = '<a href="' . $record->Huawei_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/huawei.png"> ' . $record->Huawei_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        } else {
                            $package_Huawei = '<span style="color:black;line-height:0.5"><img src="img/icon/huawei.png"> ' . $record->Huawei_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                        }
                    }
                    $statusHuawei =  '<br>'.'<span style="line-height:2.5">'.$Huawei_policy.'&nbsp;<img src="img/icon/huawei.png"> '.$policy_huawei.' '.$Huawei_status.' '. '<span class="badge badge-info">'.$dev_name_huawei.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_huawei.'</span></span>';
                }else{
                    $package_Huawei = $statusHuawei =  '';
                }
                $status = $statusChplay.$statusAmazon.$statusSamsung.$statusXiaomi. $statusOppo.$statusVivo.$statusHuawei;
                $keystore_profile =
                    '<div> Key:
                <span class="badge badge-primary" style="font-size: 12px">C: '.$record->Chplay_keystore_profile.'</span>
                <span class="badge badge-success"style="font-size: 12px">A: '.$record->Amazon_keystore_profile.'</span>
                <span class="badge badge-info"style="font-size: 12px">S: '.$record->Samsung_keystore_profile.'</span>
                <span class="badge badge-warning"style="font-size: 12px">X: '.$record->Xiaomi_keystore_profile.'</span>
                <span class="badge badge-danger"style="font-size: 12px">O: '.$record->Oppo_keystore_profile.'</span>
                <span class="badge badge-dark"style="font-size: 12px">V: '.$record->Vivo_keystore_profile.'</span>
                <span class="badge badge-primary"style="font-size: 12px">H: '.$record->Huawei_keystore_profile.'</span>
            </div>';


                $sdk_profile =
                    '<div>Sdk:
                <span class="badge badge-primary" style="font-size: 12px">C: '.$record->Chplay_sdk.'</span>
                <span class="badge badge-success"style="font-size: 12px">A: '.$record->Amazon_sdk.'</span>
                <span class="badge badge-info"style="font-size: 12px">S: '.$record->Samsung_sdk.'</span>
                <span class="badge badge-warning"style="font-size: 12px">X: '.$record->Xiaomi_sdk.'</span>
                <span class="badge badge-danger"style="font-size: 12px">O: '.$record->Oppo_sdk.'</span>
                <span class="badge badge-dark"style="font-size: 12px">V: '.$record->Vivo_sdk.'</span>
                <span class="badge badge-primary"style="font-size: 12px">H: '.$record->Huawei_sdk.'</span>
            </div>';



                if(isset($record->logo)){
//                        $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
                        $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../storage/projects/$record->ma_da/$record->projectname/lg114.png'>";

                }else{
                    $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
                }
                $abc = '<p class="text-muted" style="line-height:0.5">Ver: '.$record->buildinfo_vernum.'   |   '.$record->buildinfo_verstr.'</p>';

                $project_file  = $record->project_file ?  "    <a href='/file-manager/ProjectData/$record->project_file' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>" : '';

                if($record->des_en && $record->summary_en){
                    $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-success" style="font-size: 13px"> <img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp; </a> ';
                }elseif ($record->des_en || $record->summary_en){
                    $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-warning " style="font-size: 12px"><img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp;  </a> ';
                }else{
                    $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-secondary" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp;  </a> ';
                }

                if($record->des_vn && $record->summary_vn){
                    $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-success" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
                }elseif ($record->des_vn || $record->summary_vn){
                    $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-warning" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
                }else{
                    $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-secondary" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
                }

                $data_arr[] = array(
                    "ngocphandang_project.created_at" => $record->created_at,
                    "logo" => $logo,
                    "log" => $full_mess,
                    "name_projectname"=>$record->projectname,
                    "template"=>$data_template,
                    "projectname"=>$data_projectname.$data_template.' - '. $data_title_app. $project_file.$buildinfo_app_name_x.$abc.$sdk_profile.$keystore_profile.$des_en. $des_vn,
                    "Chplay_package" =>$package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo.$package_Huawei,
                    "status" => $status,
                    'Chplay_buildinfo_store_name_x' => $dev_name_chplay,
                    'Amazon_buildinfo_store_name_x' => $dev_name_amazon,
                    'Samsung_buildinfo_store_name_x' => $dev_name_samsung,
                    'Xiaomi_buildinfo_store_name_x' => $dev_name_xiaomi,
                    'Oppo_buildinfo_store_name_x' => $dev_name_oppo,
                    'Vivo_buildinfo_store_name_x' => $dev_name_vivo,
                    'Huawei_buildinfo_store_name_x' => $dev_name_huawei,
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

    }

    public function getIndex_partTime(Request $request){
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

        $totalRecords = ProjectModel::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')

            ->where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')

            ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = ProjectModel::with('log','dev_chplay.ga','dev_amazon.ga','dev_samsung.ga','dev_xiaomi.ga','dev_oppo.ga','dev_vivo.ga','dev_huawei.ga')
            ->orderBy($columnName, $columnSortOrder)

            ->where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')

            ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->latest('ngocphandang_project.created_at')
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="editProject_partTime('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';

            if($record->dev_chplay){
                if($record->dev_chplay->ga){
                    $ga_name_chplay = $record->dev_chplay->ga->ga_name;
                }else{
                    $ga_name_chplay = '';
                }
                $dev_name_chplay = $record->dev_chplay->dev_name;
            }else{
                $dev_name_chplay = '';
                $ga_name_chplay = '';
            }
            if($record->dev_amazon){
                if($record->dev_amazon->ga){
                    $ga_name_amazon = $record->dev_amazon->ga->ga_name;
                }else{
                    $ga_name_amazon = '';
                }
                $dev_name_amazon = $record->dev_amazon->amazon_dev_name;
            }else{
                $dev_name_amazon = '';
                $ga_name_amazon = '';
            }
            if($record->dev_samsung ){
                if($record->dev_samsung->ga){
                    $ga_name_samsung = $record->dev_samsung->ga->ga_name;
                }else{
                    $ga_name_samsung = '';
                }
                $dev_name_samsung = $record->dev_samsung->samsung_dev_name;
            }else{
                $dev_name_samsung = '';
                $ga_name_samsung = '';
            }
            if($record->dev_xiaomi){
                if($record->dev_xiaomi->ga){
                    $ga_name_xiaomi = $record->dev_xiaomi->ga->ga_name;
                }else{
                    $ga_name_xiaomi = '';
                }
                $dev_name_xiaomi = $record->dev_xiaomi->xiaomi_dev_name;
            }else{
                $dev_name_xiaomi = '';
                $ga_name_xiaomi = '';
            }
            if($record->dev_oppo){
                if($record->dev_oppo->ga){
                    $ga_name_oppo = $record->dev_oppo->ga->ga_name;
                }else{
                    $ga_name_oppo = '';
                }
                $dev_name_oppo = $record->dev_oppo->oppo_dev_name;
            }else{
                $dev_name_oppo = '';
                $ga_name_oppo = '';
            }
            if($record->dev_vivo){
                if($record->dev_vivo->ga){
                    $ga_name_vivo = $record->dev_vivo->ga->ga_name;
                }else{
                    $ga_name_vivo = '';
                }
                $dev_name_vivo = $record->dev_vivo->vivo_dev_name;
            }else{
                $dev_name_vivo = '';
                $ga_name_vivo = '';
            }
            if($record->dev_huawei){
                if($record->dev_huawei->ga){
                    $ga_name_huawei = $record->dev_huawei->ga->ga_name;
                }else{
                    $ga_name_huawei = '';
                }
                $dev_name_huawei = $record->dev_huawei->huawei_dev_name;
            }else{
                $dev_name_huawei = '';
                $ga_name_huawei = '';
            }

//            if (isset($record->link_store_vietmmo)){
//                $data_ma_da = '<a href="'.$record->link_store_vietmmo.'" target="_blank" > <p class="text-muted" style="line-height:0.5">Mã Dự án: '.$record->ma_da.'</p></a>';
//            }else{
//                $data_ma_da = '<p class="text-muted" style="line-height:0.5">Mã Dự án: '.$record->ma_da.'</p>';
//            }
//
//            if (isset($record->link_store_vietmmo)){
//                $data_template =  '<a href="'.$record->link_store_vietmmo.'" target="_blank" ><p class="text-muted" style="line-height:0.5">Template: '.$record->template.'</p></a>';
//            }else{
//                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$record->template.'</p>';
//            }

            if(isset($record->projectname)) {
                $data_projectname =   '<span style="line-height:3">Mã Project: ' . $record->projectname . '</span>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">Title App: '.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }

            if(isset($record->buildinfo_app_name_x)) {
                $data_app_name_x=  '<p class="text-muted" style="line-height:0.5">App name: '.$record->buildinfo_app_name_x.'</p>';
            }else{
                $data_app_name_x='';
            }

            if ($record['Chplay_status']==0  ) {
                $Chplay_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Chplay_status']== 1){
                $Chplay_status = '<span class="badge badge-success">Publish</span>';
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
                $Chplay_status =  '<span class="badge badge-dark">Reject</span>';
            }
            elseif($record['Chplay_status']==6){
                $Chplay_status =  '<span class="badge badge-danger">Check</span>';
            }
            elseif($record['Chplay_status']==7){
                $Chplay_status =  '<span class="badge badge-warning">Pending</span>';
            }
            if ($record['Amazon_status']==0  ) {
                $Amazon_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Amazon_status']== 1){
                $Amazon_status = '<span class="badge badge-success">Publish</span>';

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
                $Amazon_status =  '<span class="badge badge-dark">Reject</span>';
            }
            elseif($record['Amazon_status']==6){
                $Amazon_status =  '<span class="badge badge-danger">Check</span>';
            }

            if ($record['Samsung_status']==0  ) {
                $Samsung_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Samsung_status']== 1){
                $Samsung_status = '<span class="badge badge-success">Publish</span>';

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
                $Samsung_status =  '<span class="badge badge-dark">Reject</span>';
            }
            elseif($record['Samsung_status']==6){
                $Samsung_status =  '<span class="badge badge-danger">Check</span>';
            }

            if ($record['Xiaomi_status']==0  ) {
                $Xiaomi_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Xiaomi_status']== 1){
                $Xiaomi_status = '<span class="badge badge-success">Publish</span>';

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
                $Xiaomi_status =  '<span class="badge badge-dark">Reject</span>';
            }
            elseif($record['Xiaomi_status']==6){
                $Xiaomi_status =  '<span class="badge badge-danger">Check</span>';
            }


            if ($record['Oppo_status']==0  ) {
                $Oppo_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Oppo_status']== 1){
                $Oppo_status = '<span class="badge badge-success">Publish</span>';

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
                $Oppo_status =  '<span class="badge badge-dark">Reject</span>';
            }
            elseif($record['Oppo_status']==6){
                $Oppo_status =  '<span class="badge badge-danger">Check</span>';
            }


            if ($record['Vivo_status']==100  ) {
                $Vivo_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Vivo_status']== 0){
                $Vivo_status = '<span class="badge badge-warning">UnPublished</span>';
            }
            elseif($record['Vivo_status']==1){
                $Vivo_status =  '<span class="badge badge-success">Published</span>';
            }
            elseif($record['Vivo_status']==2){
                $Vivo_status =  '<span class="badge badge-danger">Removed</span>';
            }
            elseif($record['Vivo_status']==3){
                $Vivo_status =  '<span class="badge badge-primary">To be published</span>';
            }

            if ($record['Huawei_status']==100  ) {
                $Huawei_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Huawei_status']== 0){
                $Huawei_status = '<span class="badge badge-success">Released</span>';
            }
            elseif($record['Huawei_status']== 1){
                $Huawei_status = '<span class="badge badge-danger">Release Rejected</span>';
            }
            elseif($record['Huawei_status']==2){
                $Huawei_status =  '<span class="badge badge-warning">Removed (including forcible removal)</span>';
            }
            elseif($record['Huawei_status']==3){
                $Huawei_status =  '<span class="badge badge-success">Releasing</span>';
            }
            elseif($record['Huawei_status']==4){
                $Huawei_status =  '<span class="badge badge-warning">Reviewing</span>';
            }
            elseif($record['Huawei_status']==5){
                $Huawei_status =  '<span class="badge badge-warning">Updating</span>';
            }
            elseif($record['Huawei_status']==6){
                $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
            }
            elseif($record['Huawei_status']==7){
                $Huawei_status =  '<span class="badge badge-warning">Draft</span>';
            }
            elseif($record['Huawei_status']==8){
                $Huawei_status =  '<span class="badge badge-warning">Update rejected</span>';
            }
            elseif($record['Huawei_status']==9){
                $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
            }
            elseif($record['Huawei_status']==10){
                $Huawei_status =  '<span class="badge badge-danger">Removed by developer</span>';
            }
            elseif($record['Huawei_status']==11){
                $Huawei_status =  '<span class="badge badge-danger">Release canceled</span>';
            }

            if(isset($record->Chplay_policy)){
                $Chplay_policy = "<a href='$record->Chplay_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Chplay_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }
            if(isset($record->Amazon_policy)){
                $Amazon_policy = "<a href='$record->Amazon_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Amazon_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }
            if(isset($record->Samsung_policy)){
                $Samsung_policy = "<a href='$record->Samsung_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Samsung_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }
            if(isset($record->Xiaomi_policy)){
                $Xiaomi_policy = "<a href='$record->Xiaomi_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Xiaomi_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }
            if(isset($record->Oppo_policy)){
                $Oppo_policy = "<a href='$record->Oppo_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Oppo_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }
            if(isset($record->Vivo_policy)){
                $Vivo_policy = "<a href='$record->Vivo_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Vivo_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }

            if(isset($record->Huawei_policy)){
                $Huawei_policy = "<a href='$record->Huawei_policy' target='_blank' <i style='color:green;' class='mdi mdi-check-circle-outline'></i></a>";
            }else{
                $Huawei_policy = "<i style='color:red;' class='mdi mdi-close-circle-outline'></i>";
            }

            if(isset($record->policy1) || isset($record->policy2)){
                $policy_chplay = ' <a href="javascript:void(0)" onclick="showPolicy_Chplay('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_amazon = ' <a href="javascript:void(0)" onclick="showPolicy_Amazon('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_xiaomi = ' <a href="javascript:void(0)" onclick="showPolicy_Xiaomi('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_samsung = ' <a href="javascript:void(0)" onclick="showPolicy_Samsung('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_oppo = ' <a href="javascript:void(0)" onclick="showPolicy_Oppo('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_vivo = ' <a href="javascript:void(0)" onclick="showPolicy_Vivo('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
                $policy_huawei = ' <a href="javascript:void(0)" onclick="showPolicy_Huawei('.$record->projectid.')"><span class="badge badge-primary">Policy</span></a> ';
            }else{
                $policy_chplay = $policy_amazon = $policy_xiaomi = $policy_samsung = $policy_oppo = $policy_vivo = $policy_huawei = "";
            }
            if($record->Chplay_package){
                if(isset(json_decode($record->Chplay_ads,true)['ads_id'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_banner'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_inter'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_native'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_open'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_reward'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_start'])
                ){
                    if($record->Chplay_buildinfo_link_app){
                        $package_chplay = '<a href="'.$record->Chplay_buildinfo_link_app.'" target="_blank"> <span id="package_chplay" style="color:green;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_chplay = '<span id="package_chplay" style="color:orange;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else{
                    if($record->Chplay_buildinfo_link_app){
                        $package_chplay = '<a href="'.$record->Chplay_buildinfo_link_app.'" target="_blank"> <span style="color:red;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span></a> <button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_chplay = '<span style="color:black;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</span> <button onclick="copyPackage(this)" type="button" data-text="'.$record->Chplay_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }
                $statusChplay  =  '<span style="line-height:2.5">'.$Chplay_policy.'&nbsp;<img src="img/icon/google.png"> '.$policy_chplay.' '.$Chplay_status.' '. '<span class="badge badge-info">'.$dev_name_chplay.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_chplay.'</span></span>';
            }else{
                $package_chplay = $statusChplay = '';
            }

            if($record->Amazon_package){
                if(isset(json_decode($record->Amazon_ads,true)['ads_id'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_banner'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_inter'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_native'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_open'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_reward'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_start'])
                ){
                    if($record->Amazon_buildinfo_link_app){
                        $package_amazon = '<a href="'.$record->Amazon_buildinfo_link_app.'" target="_blank"><span  style="color:green;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_amazon = '<span style="color:orange;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else{

                    if($record->Amazon_buildinfo_link_app){
                        $package_amazon = '<a href="'.$record->Amazon_buildinfo_link_app.'" target="_blank"><span  style="color:red;line-height:0.5"><img src="img/icon/amazon.png">'.$record->Amazon_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_amazon = '<span style="color:black;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Amazon_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }

                $statusAmazon = '<br>'.'<span style="line-height:2.5">'.$Amazon_policy.'&nbsp;<img src="img/icon/amazon.png"> '.$policy_amazon.' '.$Amazon_status.' '. '<span class="badge badge-info">'.$dev_name_amazon.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_amazon.'</span></span>';
            }else{
                $package_amazon = $statusAmazon= '';
            }

            if($record->Samsung_package){
                if(isset(json_decode($record->Samsung_ads,true)['ads_id'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_banner'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_inter'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_native'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_open'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_reward'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_start'])
                ){
                    if($record->Samsung_buildinfo_link_app){
                        $package_samsung = '<a href="'.$record->Samsung_buildinfo_link_app.'" target="_blank"><span  style="color:green;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_samsung = '<span style="color:orange;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else{
                    if($record->Samsung_buildinfo_link_app){
                        $package_samsung = '<a href="'.$record->Samsung_buildinfo_link_app.'" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_samsung = '<span style="color:black;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Samsung_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }
                $statusSamsung =  '<br>'.'<span style="line-height:2.5">'.$Samsung_policy.'&nbsp;<img src="img/icon/samsung.png"> '.$policy_samsung.' '.$Samsung_status.' '. '<span class="badge badge-info">'.$dev_name_samsung.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_samsung.'</span></span>';
            }else{
                $package_samsung = $statusSamsung = '';
            }

            if ($record->Xiaomi_package){
                if(isset(json_decode($record->Xiaomi_ads,true)['ads_id'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_banner'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_inter'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_native'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_open'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_reward'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_start'])
                ){
                    if($record->Xiaomi_buildinfo_link_app){
                        $package_xiaomi = '<a href="'.$record->Xiaomi_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_xiaomi = '<span style="color:orange;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else {
                    if ($record->Xiaomi_buildinfo_link_app) {
                        $package_xiaomi = '<a href="' . $record->Xiaomi_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/xiaomi.png"> ' . $record->Xiaomi_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    } else {
                        $package_xiaomi = '<span style="color:black;line-height:0.5"><img src="img/icon/xiaomi.png"> ' . $record->Xiaomi_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Xiaomi_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }
                $statusXiaomi =  '<br>'.'<span style="line-height:2.5">'.$Xiaomi_policy.'&nbsp;<img src="img/icon/xiaomi.png"> '.$policy_xiaomi.' '.$Xiaomi_status.' '. '<span class="badge badge-info">'.$dev_name_xiaomi.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_xiaomi.'</span></span>';
            }else{
                $package_xiaomi = $statusXiaomi = '';
            }

            if($record->Oppo_package){
                if(isset(json_decode($record->Oppo_ads,true)['ads_id'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_banner'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_inter'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_native'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_open'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_reward'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_start'])
                ){
                    if($record->Oppo_buildinfo_link_app){
                        $package_oppo = '<a href="'.$record->Oppo_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_oppo = '<span style="color:orange;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else {
                    if ($record->Oppo_buildinfo_link_app) {
                        $package_oppo = '<a href="' . $record->Oppo_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/oppo.png"> ' . $record->Oppo_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    } else {
                        $package_oppo = '<span style="color:black;line-height:0.5"><img src="img/icon/oppo.png"> ' . $record->Oppo_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Oppo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }
                $statusOppo = '<br>'.'<span style="line-height:2.5">'.$Oppo_policy.'&nbsp;<img src="img/icon/oppo.png"> '.$policy_oppo.' '.$Oppo_status.' '. '<span class="badge badge-info">'.$dev_name_oppo.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_oppo.'</span></span>';
            }else{
                $package_oppo = $statusOppo = '';
            }


            if($record->Vivo_package){
                if(isset(json_decode($record->Vivo_ads,true)['ads_id'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_banner'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_inter'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_native'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_open'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_reward'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_start'])
                ){
                    if($record->Vivo_buildinfo_link_app){
                        $package_vivo = '<a href="'.$record->Vivo_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_vivo = '<span style="color:orange;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else{
                    if ($record->Vivo_buildinfo_link_app) {
                        $package_vivo = '<a href="' . $record->Vivo_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/vivo.png"> ' . $record->Vivo_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    } else {
                        $package_vivo = '<span style="color:black;line-height:0.5"><img src="img/icon/vivo.png"> ' . $record->Vivo_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Vivo_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }

                $statusVivo =  '<br>'.'<span style="line-height:2.5">'.$Vivo_policy.'&nbsp;<img src="img/icon/vivo.png"> '.$policy_vivo.' '.$Vivo_status.' '. '<span class="badge badge-info">'.$dev_name_vivo.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_vivo.'</span></span>';
            }else{
                $package_vivo =  $statusVivo = '';
            }

            if($record->Huawei_package){
                if(isset(json_decode($record->Huawei_ads,true)['ads_id'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_banner'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_inter'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_native'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_open'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_reward'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_start'])
                ){
                    if($record->Huawei_buildinfo_link_app){
                        $package_Huawei = '<a href="'.$record->Huawei_buildinfo_link_app.'" target="_blank"><span style="color:green;line-height:0.5"><img src="img/icon/huawei.png"> '.$record->Huawei_package.'</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }else{
                        $package_Huawei = '<span style="color:orange;line-height:0.5"><img src="img/icon/huawei.png"> ' .$record->Huawei_package.'</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }else{
                    if ($record->Huawei_buildinfo_link_app) {
                        $package_Huawei = '<a href="' . $record->Huawei_buildinfo_link_app . '" target="_blank"><span style="color:red;line-height:0.5"><img src="img/icon/huawei.png"> ' . $record->Huawei_package . '</span></a><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    } else {
                        $package_Huawei = '<span style="color:black;line-height:0.5"><img src="img/icon/huawei.png"> ' . $record->Huawei_package . '</span><button onclick="copyPackage(this)" type="button" data-text="'.$record->Huawei_package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';
                    }
                }
                $statusHuawei =  '<br>'.'<span style="line-height:2.5">'.$Huawei_policy.'&nbsp;<img src="img/icon/huawei.png"> '.$policy_huawei.' '.$Huawei_status.' '. '<span class="badge badge-info">'.$dev_name_huawei.'</span>'.' '. '<span class="badge badge-warning">'.$ga_name_huawei.'</span></span>';
            }else{
                $package_Huawei = $statusHuawei =  '';
            }
            $status = $statusChplay.$statusAmazon.$statusSamsung.$statusXiaomi. $statusOppo.$statusVivo.$statusHuawei;


            if(isset($record->logo)){
                if (isset($record->link_store_vietmmo)){
                    $logo = "<a href='".$record->link_store_vietmmo."' target='_blank'>  <img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'></a>";
                }else{
                    $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
                }
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }


            if($record->des_en && $record->summary_en){
                $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-success" style="font-size: 13px"> <img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp; </a> ';
            }elseif ($record->des_en || $record->summary_en){
                $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-warning " style="font-size: 12px"><img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp;  </a> ';
            }else{
                $des_en = '<a href="javascript:void(0)" onclick="editProject_Description_EN('.$record->projectid.')" class="badge badge-secondary" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/united-states.png" alt="Header Language" height="13">EN &nbsp;&nbsp;&nbsp;  </a> ';
            }

            if($record->des_vn && $record->summary_vn){
                $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-success" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
            }elseif ($record->des_vn || $record->summary_vn){
                $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-warning" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
            }else{
                $des_vn = ' <a href="javascript:void(0)" onclick="editProject_Description_VN('.$record->projectid.')" class="badge badge-secondary" style="font-size: 12px"> <img class="mr-3" src="assets/images/flags/vietnam.png" alt="Header Language" height="13">VN &nbsp;&nbsp;&nbsp;  </a>';
            }

            $data_arr[] = array(
                "ngocphandang_project.created_at" => $record->created_at,
                "logo" => $logo,
                "log" => '',
                "name_projectname"=>$record->projectname,
//                "template"=>$data_template,
                "projectname"=>$data_projectname.$data_title_app.$data_app_name_x.$des_en. $des_vn,
                "Chplay_package" =>$package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo.$package_Huawei,
                "status" => $status,
                'Chplay_buildinfo_store_name_x' => $dev_name_chplay,
                'Amazon_buildinfo_store_name_x' => $dev_name_amazon,
                'Samsung_buildinfo_store_name_x' => $dev_name_samsung,
                'Xiaomi_buildinfo_store_name_x' => $dev_name_xiaomi,
                'Oppo_buildinfo_store_name_x' => $dev_name_oppo,
                'Vivo_buildinfo_store_name_x' => $dev_name_vivo,
                'Huawei_buildinfo_store_name_x' => $dev_name_huawei,
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

//        dd($request->all());
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


        if(isset($request->remove_status)){
            $status_console = $request->remove_status;
            $status_console = explode('%',$status_console);
            $projects = ProjectModel::whereIn('buildinfo_console',$status_console)->get();
            foreach ($projects as $project){
                ProjectModel::updateOrCreate(
                    [
                        "projectid" => $project->projectid,
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
            }
        }

        if(isset($request->console_status)){
            $status_console = $request->console_status;
            $status_console = explode('%',$status_console);
            // Total records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where(function ($q){
                    $q->where('buildinfo_console','<>',0);
                })
                ->where(function ($q) use ($status_console) {
                    $q->whereIn('buildinfo_console',$status_console);
                })
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where(function ($a) use ($searchValue) {
                    $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%');
                })
                ->where(function ($q){
                    $q->where('buildinfo_console','<>',0);
                })

                ->where(function ($q) use ($status_console) {
                    $q->whereIn('buildinfo_console',$status_console);
                })
                ->count();

            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

                ->where(function ($a) use ($searchValue) {
                    $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%');
                })
                ->where(function ($q){
                    $q->where('buildinfo_console','<>',0);
                })
                ->where(function ($q) use ($status_console) {
                    $q->whereIn('buildinfo_console',$status_console);
                })
                ->select('ngocphandang_project.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

        }
        else{
            // Total records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where(function ($q){
                    $q->where('buildinfo_console','<>',0);
                })
                ->count();

            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where(function ($a) use ($searchValue) {
                    $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.buildinfo_console', $searchValue );
                })
                ->where(function ($q){
                    $q->where('ngocphandang_project.buildinfo_console','<>',0);
                })
                ->count();


            // Get records, also we have included search filter as well
//            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
//                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
//                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
//
//                ->where(function ($a) use ($searchValue) {
//                    $a
//                        ->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
//                        ->orWhere('ngocphandang_project.buildinfo_console', 'like', '%' . $searchValue . '%');
//
//                })
//                ->where(function ($q){
//                    $q->where('ngocphandang_project.buildinfo_console','<>',0);
//                })
//                ->select('ngocphandang_project.*')
//                ->skip($start)
//                ->take($rowperpage)
//                ->get();
//            dd($records);



            $records = ProjectModel::with('da','matemplate','log','dev_chplay.ga','dev_amazon.ga','dev_samsung.ga','dev_xiaomi.ga','dev_oppo.ga','dev_vivo.ga','dev_huawei.ga')
                ->orderBy($columnName, $columnSortOrder)
//                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
//                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

                ->where(function ($a) use ($searchValue) {
                    $a
                        ->where('ngocphandang_project.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.template', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.buildinfo_console', 'like', '%' . $searchValue . '%');

                })
                ->where(function ($q){
                    $q->where('ngocphandang_project.buildinfo_console','<>',0);
                })
                ->skip($start)
                ->latest('ngocphandang_project.created_at')
                ->take($rowperpage)
                ->get();



        }
        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-warning removeProject"><i class="mdi mdi-file-move"></i></a>';

//            $ma_da = DB::table('ngocphandang_project')
//                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
//                ->where('ngocphandang_da.id',$record->ma_da)
//                ->first();
//            $template = DB::table('ngocphandang_project')
//                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
//                ->where('ngocphandang_template.id',$record->template)
//                ->first();

            if (isset($record->link_store_vietmmo)){
                $data_ma_da = '<a href="'.$record->link_store_vietmmo.'" target="_blank" > <p class="text-muted" style="line-height:0.5">Mã Dự án: '.$record->da->ma_da.'</p></a>';
            }else{
                $data_ma_da = '<p class="text-muted" style="line-height:0.5">Mã Dự án: '.$record->da->ma_da.'</p>';
            }

            if (isset($record->link_store_vietmmo)){
                $data_template =  '<a href="'.$record->link_store_vietmmo.'" target="_blank" ><p class="text-muted" style="line-height:0.5">Template: '.$record->matemplate->template.'</p></a>';
            }else{
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$record->matemplate->template.'</p>';
            }



            if(isset($record->projectname)) {
                $data_projectname =   '<span style="line-height:3"> Mã Project: ' . $record->projectname . '</span>';

            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }

            $abc = '<p class="text-muted" style="line-height:0.5">'.$record->buildinfo_keystore. '   |   '.$record->buildinfo_vernum.'   |   '.$record->buildinfo_verstr.'</p>';


            if($record->Chplay_package){
                if(isset(json_decode($record->Chplay_ads,true)['ads_id'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_banner'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_inter'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_native'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_open'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_reward'])
                    || isset(json_decode($record->Chplay_ads,true)['ads_start'])
                ){
                    $package_chplay = '<p style="color:green;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</p>';
                }else{
                    $package_chplay = '<p style="color:red;line-height:0.5"><img src="img/icon/google.png"> '.$record->Chplay_package.'</p>';
                }
            }else{
                $package_chplay = '';
            }


            if ($record->Amazon_package){
                if(isset(json_decode($record->Amazon_ads,true)['ads_id'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_banner'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_inter'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_native'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_open'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_reward'])
                    || isset(json_decode($record->Amazon_ads,true)['ads_start'])
                ){
                    $package_amazon = '<p  style="color:green;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</p>';
                }else{
                    $package_amazon = '<p style="color:red;line-height:0.5"><img src="img/icon/amazon.png"> '.$record->Amazon_package.'</p>';
                }
            }else{
                $package_amazon = '';
            }


            if ($record->Samsung_package){
                if(isset(json_decode($record->Samsung_ads,true)['ads_id'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_banner'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_inter'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_native'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_open'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_reward'])
                    || isset(json_decode($record->Samsung_ads,true)['ads_start'])
                ){
                    $package_samsung = '<p style="color:green;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</p>';
                }else{
                    $package_samsung = '<p style="color:red;line-height:0.5"><img src="img/icon/samsung.png"> '.$record->Samsung_package.'</p>';
                }
            }else{
                $package_samsung ='';
            }


            if ($record->Xiaomi_package){
                if(isset(json_decode($record->Xiaomi_ads,true)['ads_id'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_banner'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_inter'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_native'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_open'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_reward'])
                    || isset(json_decode($record->Xiaomi_ads,true)['ads_start'])
                ){
                    $package_xiaomi = '<p style="color:green;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</p>';
                }else{
                    $package_xiaomi = '<p style="color:red;line-height:0.5"><img src="img/icon/xiaomi.png"> '.$record->Xiaomi_package.'</p>';
                }
            }else{
                $package_xiaomi = '';
            }




            if($record->Oppo_package){
                if(isset(json_decode($record->Oppo_ads,true)['ads_id'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_banner'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_inter'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_native'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_open'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_reward'])
                    || isset(json_decode($record->Oppo_ads,true)['ads_start'])
                ){
                    $package_oppo = '<p style="color:green;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</p>';
                }else{
                    $package_oppo = '<p style="color:red;line-height:0.5"><img src="img/icon/oppo.png"> '.$record->Oppo_package.'</p>';
                }
            }else{
                $package_oppo ='';
            }



            if ($record->Vivo_package){
                if(isset(json_decode($record->Vivo_ads,true)['ads_id'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_banner'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_inter'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_native'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_open'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_reward'])
                    || isset(json_decode($record->Vivo_ads,true)['ads_start'])
                ){
                    $package_vivo = '<p style="color:green;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</p>';
                }else{
                    $package_vivo = '<p style="color:red;line-height:0.5"><img src="img/icon/vivo.png"> '.$record->Vivo_package.'</p>';
                }
            }else{
                $package_vivo = '';
            }


            if($record->Huawei_package){
                if(isset(json_decode($record->Huawei_ads,true)['ads_id'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_banner'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_inter'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_native'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_open'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_reward'])
                    || isset(json_decode($record->Huawei_ads,true)['ads_start'])
                ){
                    $package_Huawei = '<p style="color:green;line-height:0.5"><img src="img/icon/huawei.png"> '.$record->Huawei_package.'</p>';
                }else{
                    $package_Huawei = '<p style="color:red;line-height:0.5"><img src="img/icon/huawei.png"> '.$record->Huawei_package.'</p>';
                }
            }else{
                $package_Huawei = '';
            }



//
            if(isset($record->logo)){
                if (isset($record->link_store_vietmmo)){
                    $logo = "<a href='".$record->link_store_vietmmo."' target='_blank'>  <img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'></a>";
                }else{
                    $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
                }
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }
            $mess_info = '';
            $full_mess ='null';
            if ($record->buildinfo_mess){
                $buildinfo_mess = $record->buildinfo_mess;
                $full_mess =  (str_replace('|','<br>',$buildinfo_mess));
                $buildinfo_mess =  (explode('|',$buildinfo_mess));
                $buildinfo_mess = array_reverse($buildinfo_mess);
                for($i = 0 ; $i < 6 ; $i++){
                    if(isset($buildinfo_mess[$i])){
                        $mess_info .=  $buildinfo_mess[$i].'<br>';
                    }
                }
            }

            $data_arr[] = array(
                "created_at" => $record->created_at,
                "logo" => $logo,
                "projectid"=>$record->projectid,
                "name_projectname"=>$record->projectname,
                "projectname"=>$data_projectname.$data_template.$data_ma_da.$data_title_app.$abc,
                "package" => $package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo.$package_Huawei,
                "buildinfo_mess" => $mess_info,
                "full_mess" => $full_mess,
                "buildinfo_console" =>$record->buildinfo_console,
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
    public function getChplay(Request $request)
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

        if (isset($request->status_app)){
            // Total records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where('ngocphandang_project.Chplay_package','<>','null')
                ->where('ngocphandang_project.Chplay_status','=',$request->status_app)
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where(function ($a) use ($searchValue) {
                    $a->Where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%');
                })
                ->where('ngocphandang_project.Chplay_package','<>',null)
                ->where('ngocphandang_project.Chplay_status','=',$request->status_app)
                ->count();
            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->where(function ($a) use ($searchValue) {
                    $a->Where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%');
                })
                ->where('ngocphandang_project.Chplay_package','<>',null)
                ->where('ngocphandang_project.Chplay_status','=',$request->status_app)
                ->select('ngocphandang_project.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }    else {
        // Total records
        $totalRecords = ProjectModel::select('count(*) as allcount')
            ->where('ngocphandang_project.Chplay_package','<>',null)
            ->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->where(function ($a) use ($searchValue) {
                $a->Where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Chplay_package','<>',null)
            ->count();
        // Get records, also we have included search filter as well
        $records = ProjectModel::orderBy($columnName, $columnSortOrder)
            ->where(function ($a) use ($searchValue) {
                $a->Where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orwhereJsonContains('Chplay_bot->installs', $searchValue)
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.buildinfo_keystore', 'like', '%' . $searchValue . '%');;
//                    ->orWhere('ngocphandang_project.Chplay_package', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Chplay_package','<>',null)
            ->select('ngocphandang_project.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
    }
        $data_arr = array();

        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="detailChplay('.$record->projectid.')" class="btn btn-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            if($record->Chplay_status == 0 || $record->Chplay_status == 3 ){
                $check = ' <a href="javascript:void(0)" onclick="checkbox('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-check-box-outline"></i></a>';
            }else{
                $check = '';
            }
            if(isset($record->projectname)) {
                $data_projectname =  '<p style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }
            $package_chplay = '<a href="http://play.google.com/store/apps/details?id='.$record->Chplay_package.'" target="_blank"<p style="line-height:0.5">'.$record->Chplay_package.'</p></a>';
            if ($record['buildinfo_console']== 0  ) {
                $buildinfo_console = 'Trạng thái tĩnh';
            }
            elseif($record['buildinfo_console']== 1){
                $buildinfo_console = '<span class="badge badge-dark">Build App</span>';
            }
            elseif($record['buildinfo_console']== 2){
                $buildinfo_console =  '<span class="badge badge-warning">Đang xử lý Build App</span>';
            }
            elseif($record['buildinfo_console']== 3){
                $buildinfo_console =  '<span class="badge badge-info">Kết thúc Build App</span>';
            }
            elseif($record['buildinfo_console']== 4){
                $buildinfo_console =  '<span class="badge badge-primary">Check Data Project</span>';
            }
            elseif($record['buildinfo_console']== 5){
                $buildinfo_console =  '<span class="badge badge-success">Đang xử lý check dữ liệu của Project</span>';
            }
            elseif($record['buildinfo_console']== 6){
                $buildinfo_console =  '<span class="badge badge-danger">Kết thúc Check</span>';
            }
            elseif($record['buildinfo_console']== 7){
                $buildinfo_console =  '<span class="badge badge-danger">Build App (Thất bại)</span>';
            }
            elseif($record['buildinfo_console']== 8){
                $buildinfo_console =  '<span class="badge badge-danger">Kết thúc (Dự liệu thiếu) </span>';
            }

            if ($record['Chplay_status'] ==0  ) {
                $Chplay_status = 'Chưa Publish';
            }
            elseif($record['Chplay_status']== 1){
                $Chplay_status = '<span class="badge badge-dark">Public</span>';
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
            elseif($record['Chplay_status']==7){
                $Chplay_status =  '<span class="badge badge-warning">Pending</span>';
            }

            $mess_info = '';
            $full_mess ='';
            if ($record->buildinfo_mess){
                $buildinfo_mess = $record->buildinfo_mess;
                $full_mess =  (str_replace('|','<br>',$buildinfo_mess));
                $buildinfo_mess =  (explode('|',$buildinfo_mess));
                $buildinfo_mess = array_reverse($buildinfo_mess);
                for($i = 0 ; $i < 6 ; $i++){
                    if(isset($buildinfo_mess[$i])){
                        $mess_info .=  $buildinfo_mess[$i].'<br>';
                    }
                }
            }

            if(isset($record->logo)){
                $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }
            if(isset($record->Chplay_bot)){
                $bot = json_decode($record->Chplay_bot,true);
                if(isset($bot['logo'])){
                    $logo = $bot['logo'];
                    $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="'.$logo.'">';
                }

                $version_bot    = isset($bot['appVersion']) ? $bot['appVersion'] : 0;
                $version_build  = $record->buildinfo_verstr;


                if($version_bot == $version_build ){
                    $version = 'Version: <span class="badge badge-success">'.$version_bot.'</span>';
                }else{
                    $version = 'Version Bot:  <span class="badge badge-danger">'.$version_bot.'</span> ' .  ' <br> Version Build:   <span class="badge badge-secondary">'.$version_build.'</span> ';
                }



                $data_arr[] = array(
                    "updated_at" => strtotime($record->updated_at),
                    "logo" => $logo,
                    "name_projectname"=>$record->projectname,
                    "title_app"=>$record->title_app,
                    "buildinfo_keystore"=>$record->buildinfo_keystore,
                    "ma_da"=>$data_projectname.'<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>'.'<p class="text-muted" style="line-height:0.5">'.$record->buildinfo_keystore.'</p>'.$package_chplay,
                    "Chplay_bot->installs" => isset($bot['installs']) ? $bot['installs'] : 0,
                    "Chplay_bot->numberVoters" => isset($bot['numberVoters']) ? $bot['numberVoters'] : 0,
                    "Chplay_bot->numberReviews" => isset($bot['numberReviews']) ? $bot['numberReviews'] :0,
                    "Chplay_bot->score" => isset($bot['score'])? number_format($bot['score'],2):0,
                    "buildinfo_mess" => $mess_info,
                    "full_mess" => $full_mess,
                    "status" =>'Console: '.$buildinfo_console.'<br> Ứng dụng: '.$Chplay_status.'<br>'.$version. '<br> Time check: '.date('H:i:s   d-m-Y',$record->bot_timecheck),
                    "action"=> $btn .'  '. $check,
                );
            }else{

                $data_arr[] = array(
                    "updated_at" => strtotime($record->updated_at),
                    "logo" => $logo,
                    "name_projectname"=>$record->projectname,
                    "ma_da"=>$data_projectname.$package_chplay,
                    "Chplay_bot->installs" => 0,
                    "Chplay_bot->numberVoters" => 0,
                    "Chplay_bot->numberReviews" => 0,
                    "Chplay_bot->score" => 0,
                    "buildinfo_mess" => $mess_info,
                    "full_mess" => $full_mess,
                    "status" =>'Console: '.$buildinfo_console.'<br> Ứng dụng: '.$Chplay_status,
                    "action"=> $btn .'  '. $check,
                );
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );


        echo json_encode($response);
    }
    public function getAmazon(Request $request)
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
        $totalRecords = ProjectModel::select('count(*) as allcount')
            ->where('ngocphandang_project.Amazon_package','<>','null')
            ->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%');

            })
            ->where('ngocphandang_project.Amazon_package','<>','null')
            ->count();


        // Get records, also we have included search filter as well
        $records = ProjectModel::orderBy($columnName, $columnSortOrder)
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Amazon_package', 'like', '%' . $searchValue . '%');


            })
            ->where('ngocphandang_project.Amazon_package','<>',null)
            ->select('ngocphandang_project.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="detailAmazon('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            $ma_da = DB::table('ngocphandang_project')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            $ads = json_decode($record->Amazon_ads,true);
            $package_Amazon = '<p style="line-height:0.5">'.$record->Amazon_package.'</p>';
            $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
            $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
            $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
            $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
            $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';
//


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
                "package" => $package_Amazon.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
//                "buildinfo_mess" => $mess_info,
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
    public function getSamsung(Request $request)
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
        $totalRecords = ProjectModel::select('count(*) as allcount')
            ->where('ngocphandang_project.Samsung_package','<>','null')
            ->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Samsung_package','<>','null')
            ->count();


        // Get records, also we have included search filter as well
        $records = ProjectModel::orderBy($columnName, $columnSortOrder)
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Samsung_package', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Samsung_package','<>',null)
            ->select('ngocphandang_project.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="detailSamsung('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            $ma_da = DB::table('ngocphandang_project')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            $ads = json_decode($record->Samsung_ads,true);
            $package_Samsung = '<p style="line-height:0.5">'.$record->Samsung_package.'</p>';
            $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
            $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
            $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
            $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
            $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';
//


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
                "package" => $package_Samsung.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
//                "buildinfo_mess" => $mess_info,
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
    public function getXiaomi(Request $request)
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
        $totalRecords = ProjectModel::select('count(*) as allcount')
            ->where('ngocphandang_project.Xiaomi_package','<>','null')
            ->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Xiaomi_package','<>','null')
            ->count();


        // Get records, also we have included search filter as well
        $records = ProjectModel::orderBy($columnName, $columnSortOrder)
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Xiaomi_package', 'like', '%' . $searchValue . '%');

            })
            ->where('ngocphandang_project.Xiaomi_package','<>',null)
            ->select('ngocphandang_project.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="detailXiaomi('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            $ma_da = DB::table('ngocphandang_project')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            $ads = json_decode($record->Xiaomi_ads,true);
            $package_Xiaomi = '<p style="line-height:0.5">'.$record->Xiaomi_package.'</p>';
            $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
            $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
            $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
            $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
            $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';
//


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
                "package" => $package_Xiaomi.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
//                "buildinfo_mess" => $mess_info,
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
    public function getOppo(Request $request)
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
        $totalRecords = ProjectModel::select('count(*) as allcount')
            ->where('ngocphandang_project.Oppo_package','<>','null')
            ->count();
        $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%');
            })
            ->where('ngocphandang_project.Oppo_package','<>','null')
            ->count();


        // Get records, also we have included search filter as well
        $records = ProjectModel::orderBy($columnName, $columnSortOrder)
            ->leftjoin('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
            ->leftjoin('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')

            ->where(function ($a) use ($searchValue) {
                $a->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%')
                    ->orWhere('ngocphandang_project.Oppo_package', 'like', '%' . $searchValue . '%');

            })
            ->where('ngocphandang_project.Oppo_package','<>',null)
            ->select('ngocphandang_project.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" onclick="detailOppo('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            $ma_da = DB::table('ngocphandang_project')
                ->join('ngocphandang_da','ngocphandang_da.id','=','ngocphandang_project.ma_da')
                ->where('ngocphandang_da.id',$record->ma_da)
                ->first();
            $template = DB::table('ngocphandang_project')
                ->join('ngocphandang_template','ngocphandang_template.id','=','ngocphandang_project.template')
                ->where('ngocphandang_template.id',$record->template)
                ->first();

            if(isset($ma_da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $ma_da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($template)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$template->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            $ads = json_decode($record->Oppo_ads,true);
            $package_Oppo = '<p style="line-height:0.5">'.$record->Oppo_package.'</p>';
            $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
            $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
            $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
            $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
            $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';
//


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
                "package" => $package_Oppo.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
//                "buildinfo_mess" => $mess_info,
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
    public function getVivo(Request $request)
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

        if (isset($request->status_app)){
            $status_app = explode(',',$request->status_app);
            // aTotal records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where('ngocphandang_project.Vivo_package','<>','null')
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->with('da','matemplate')
                ->where('ngocphandang_project.Vivo_package', '<>', null)
                ->where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
//                ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
//                ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->orWhere('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->orWhere('template', 'like', '%' . $searchValue . '%');
                })
                ->whereIn('Vivo_status',$status_app)
                ->count();
            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->with('da','matemplate')
                ->where('ngocphandang_project.Vivo_package', '<>', null)
                ->where('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
//                ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
//                ->orWhere('ngocphandang_project.Vivo_package', 'like', '%' . $searchValue . '%')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->orWhere('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->orWhere('template', 'like', '%' . $searchValue . '%');
                })
                ->whereIn('Vivo_status',$status_app)
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }else {
            // Total records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->with('da','matemplate')
                ->where('Vivo_package', '<>', null)
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->with('da','matemplate')
                ->where('Vivo_package', '<>', null)
                ->where('projectname', 'like', '%' . $searchValue . '%')
                ->orWhere('title_app', 'like', '%' . $searchValue . '%')
                ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%')
                ->orwhereHas('da', function ($q) use ($searchValue) {
                    $q->Where('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('matemplate', function ($q) use ($searchValue) {
                    $q->Where('template', 'like', '%' . $searchValue . '%');
                })
                ->count();


            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->with('da','matemplate')
                ->where('Vivo_package', '<>', null)
                ->where('projectname', 'like', '%' . $searchValue . '%')
                ->orWhere('title_app', 'like', '%' . $searchValue . '%')
                ->orWhere('Vivo_package', 'like', '%' . $searchValue . '%')
                ->orwhereHas('da', function ($q) use ($searchValue) {
                    $q->Where('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('matemplate', function ($q) use ($searchValue) {
                    $q->Where('template', 'like', '%' . $searchValue . '%');
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }
        $data_arr = array();
        foreach ($records as $record) {
//            dd($record);
            $btn = ' <a href="javascript:void(0)" onclick="detailVivo('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            if(isset($record->da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $record->da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($record->matemplate)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$record->matemplate->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            try{
                $ads = json_decode($record->Vivo_ads,true);
                $package_Vivo = '<p style="line-height:0.5">'.$record->Vivo_package.'</p>';
                $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
                $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
                $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
                $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
                $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';
            }catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error('Message:' . $exception->getMessage() . '--- ads: '. $record->projectname .'----'. $exception->getLine());
            }


//


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

            if ($record['Vivo_status']==100  ) {
                $Vivo_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Vivo_status']== 0){
                $Vivo_status = '<span class="badge badge-warning">UnPublished</span>';
            }
            elseif($record['Vivo_status']==1){
                $Vivo_status =  '<span class="badge badge-success">Published</span>';
            }
            elseif($record['Vivo_status']==2){
                $Vivo_status =  '<span class="badge badge-danger">Removed</span>';
            }
            elseif($record['Vivo_status']==3){
                $Vivo_status =  '<span class="badge badge-primary">To be published</span>';
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

//            dd($record);
            $bot = json_decode($record->Vivo_bot,true);
            $version_bot    = isset($bot['versionNumber']) ? $bot['versionNumber'] : 0;
            $version_build  = $record->buildinfo_verstr;
            if($version_bot == $version_build ){
                $version = 'Version: <span class="badge badge-success">'.$version_bot.'</span>';
            }else{
                $version = 'Version Bot:  <span class="badge badge-danger">'.$version_bot.'</span> ' .  ' <br> Version Build:   <span class="badge badge-secondary">'.$version_build.'</span> ';
            }

            $data_arr[] = array(
                "updated_at" => $record->updated_at,
                "logo" => $logo,
                "ma_da"=>$data_ma_da.$data_template.$data_projectname.$data_title_app,
                "package" => $package_Vivo.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
                "Vivo_bot->time_bot" => $buildinfo_console . '</br>Ứng dụng: '.$Vivo_status. '</br> Version: '. $version. '</br> Time check:' . date('Y-m-d H:i:s',$bot['time_bot']),
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
    public function getHuawei(Request $request)
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



        if (isset($request->status_app)){
            $status_app = explode(',',$request->status_app);
            // aTotal records
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where('Huawei_package','<>',null)
                ->where('Huawei_appId','<>',null)
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->with('da','matemplate')
                ->where('projectname', 'like', '%' . $searchValue . '%')
//                ->orWhere('title_app', 'like', '%' . $searchValue . '%')
//                ->orWhere('Huawei_package', 'like', '%' . $searchValue . '%')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->orWhere('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->orWhere('template', 'like', '%' . $searchValue . '%');
                })
                ->where('Huawei_package','<>',null)
                ->where('Huawei_appId','<>',null)
                ->whereIn('Huawei_status',$status_app)
                ->count();
            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->with('da','matemplate')
                ->where('projectname', 'like', '%' . $searchValue . '%')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->Where('ma_da', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->Where('template', 'like', '%' . $searchValue . '%');
                })

                ->where('Huawei_package','<>',null)
                ->where('Huawei_appId','<>',null)
                ->whereIn('Huawei_status',$status_app)
//                ->orWhere('title_app', 'like', '%' . $searchValue . '%')
//                ->orWhere('Huawei_package', 'like', '%' . $searchValue . '%')
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

        }else{
            $totalRecords = ProjectModel::select('count(*) as allcount')
                ->where('ngocphandang_project.Huawei_package','<>',null)
                ->where('ngocphandang_project.Huawei_appId','<>',null)
                ->count();
            $totalRecordswithFilter = ProjectModel::select('count(*) as allcount')
                ->with('da','matemplate')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%');
                })
                ->where('ngocphandang_project.Huawei_package','<>',null)
                ->where('ngocphandang_project.Huawei_appId','<>',null)
                ->count();
            // Get records, also we have included search filter as well
            $records = ProjectModel::orderBy($columnName, $columnSortOrder)
                ->with('da','matemplate')
                ->whereHas('da', function ($q) use ($searchValue) {
                    $q->where('ngocphandang_da.ma_da', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.projectname', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.title_app', 'like', '%' . $searchValue . '%')
                        ->orWhere('ngocphandang_project.Huawei_package', 'like', '%' . $searchValue . '%');
                })
                ->whereHas('matemplate', function ($q) use ($searchValue) {
                    $q->orWhere('ngocphandang_template.template', 'like', '%' . $searchValue . '%');
                })
                ->where('ngocphandang_project.Huawei_package','<>',null)
                ->where('ngocphandang_project.Huawei_appId','<>',null)
                ->select('ngocphandang_project.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }

        // Total records
        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="detailHuawei('.$record->projectid.')" class="btn btn-outline-warning"><i class="mdi mdi-clipboard-text"></i></a>';
            if(isset($record->da)) {
                $data_ma_da =
                    '<span style="line-height:3"> Mã Dự án: ' . $record->da->ma_da . '</span>';
            }else{
                $data_ma_da = '';
            }
            if(isset($record->matemplate)) {
                $data_template =  '<p class="text-muted" style="line-height:0.5">Template: '.$record->matemplate->template.'</p>';
            }else{
                $data_template='';
            }

            if(isset($record->projectname)) {
                $data_projectname =  '<p class="text-muted" style="line-height:0.5">Project: '.$record->projectname.'</p>';
            }else{
                $data_projectname='';
            }

            if(isset($record->title_app)) {
                $data_title_app=  '<p class="text-muted" style="line-height:0.5">'.$record->title_app.'</p>';
            }else{
                $data_title_app='';
            }
            $ads = json_decode($record->Huawei_ads,true);
            $package_Huawei = '<p style="line-height:0.5">'.$record->Huawei_package.'</p>';
            $ads_banner = '<p class="text-muted" style="line-height:0.5">ads_banner: '.$ads['ads_banner'].'</p>';
            $ads_inter = '<p class="text-muted" style="line-height:0.5">ads_inter: '.$ads['ads_inter'].'</p>';
            $ads_native = '<p class="text-muted" style="line-height:0.5">ads_native: '.$ads['ads_native'].'</p>';
            $ads_open = '<p class="text-muted" style="line-height:0.5">ads_open: '.$ads['ads_open'].'</p>';
            $ads_reward = '<p class="text-muted" style="line-height:0.5">ads_reward: '.$ads['ads_reward'].'</p>';


            if ($record['Huawei_status']==100  ) {
                $Huawei_status = '<span class="badge badge-secondary">Mặc định</span>';
            }
            elseif($record['Huawei_status']== 0){
                $Huawei_status = '<span class="badge badge-success">Released</span>';
            }
            elseif($record['Huawei_status']== 1){
                $Huawei_status = '<span class="badge badge-danger">Release Rejected</span>';
            }
            elseif($record['Huawei_status']==2){
                $Huawei_status =  '<span class="badge badge-warning">Removed (including forcible removal)</span>';
            }
            elseif($record['Huawei_status']==3){
                $Huawei_status =  '<span class="badge badge-success">Releasing</span>';
            }
            elseif($record['Huawei_status']==4){
                $Huawei_status =  '<span class="badge badge-warning">Reviewing</span>';
            }
            elseif($record['Huawei_status']==5){
                $Huawei_status =  '<span class="badge badge-warning">Updating</span>';
            }
            elseif($record['Huawei_status']==6){
                $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
            }
            elseif($record['Huawei_status']==7){
                $Huawei_status =  '<span class="badge badge-warning">Draft</span>';
            }
            elseif($record['Huawei_status']==8){
                $Huawei_status =  '<span class="badge badge-warning">Update rejected</span>';
            }
            elseif($record['Huawei_status']==9){
                $Huawei_status =  '<span class="badge badge-warning">Removal requested</span>';
            }
            elseif($record['Huawei_status']==10){
                $Huawei_status =  '<span class="badge badge-danger">Removed by developer</span>';
            }
            elseif($record['Huawei_status']==11){
                $Huawei_status =  '<span class="badge badge-danger">Release canceled</span>';
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

            if(isset($record->Huawei_bot)){
                $bot = json_decode($record->Huawei_bot,true);
                $version_bot    = isset($bot['versionNumber']) ? $bot['versionNumber'] : 0;
                $version_build  = $record->buildinfo_verstr;
                if($version_bot == $version_build ){
                    $version = 'Version: <span class="badge badge-success">'.$version_bot.'</span>';
                }else{
                    $version = 'Version Bot:  <span class="badge badge-danger">'.$version_bot.'</span> ' .  ' <br> Version Build:   <span class="badge badge-secondary">'.$version_build.'</span> ';
                }

                $sum_download = 0;

                foreach($bot as $num => $values) {
                    if(is_numeric($num)){
                        $sum_download += $values[ 'Total_downloads' ];
                    }
                }

                $data_arr[] = array(
                    "updated_at" => $record->updated_at,
                    "logo" => $logo,
                    "install" => $sum_download,
                    "ma_da"=>$data_ma_da.$data_template.$data_projectname.$data_title_app,
                    "package" => $package_Huawei.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
                    "Huawei_bot->time_bot" =>'Console: '.$buildinfo_console.'<br> Ứng dụng: '.$Huawei_status.'<br>'.$version. '<br> Time check: '.$bot['time_bot'],
                    "action"=> $btn,
                );
            }else{
                $data_arr[] = array(
                    "updated_at" => $record->updated_at,
                    "logo" => $logo,
                    "install" => 0,
                    "ma_da"=>$data_ma_da.$data_template.$data_projectname.$data_title_app,
                    "package" => $package_Huawei.$ads_banner.$ads_inter.$ads_native.$ads_open.$ads_reward,
                    "Huawei_bot->time_bot" =>'Console: '.$buildinfo_console.'<br> Ứng dụng: '.$Huawei_status,
                    "action"=> $btn,
                );
            }
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
            'projectname' =>'required|unique:ngocphandang_project,projectname',
            'ma_da' => 'required|not_in:0',
            'template' => 'required|not_in:0',
            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
//            'logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'project_file' => 'mimes:zip',
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
            'project_file.mimes'=>'*.zip',
//            'logo.mimes'=>'Logo không đúng định dạng: jpeg, png, jpg, gif, svg.',
//            'logo.max'=>'Logo max: 2M.',
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
            'ads_open' => $request->Chplay_ads_open,
            'ads_start' => $request->Chplay_ads_start,
            'ads_banner_huawei' => $request->Chplay_ads_banner_huawei,
            'ads_inter_huawei' => $request->Chplay_ads_inter_huawei,
            'ads_reward_huawei' => $request->Chplay_ads_reward_huawei,
            'ads_native_huawei' => $request->Chplay_ads_native_huawei,
            'ads_splash_huawei' => $request->Chplay_ads_splash_huawei,
            'ads_roll_huawei' => $request->Chplay_ads_roll_huawei,
        ];
        $Chplay_ads =  json_encode($Chplay_ads);

        $Amazon_ads = [
            'ads_id' => $request->Amazon_ads_id,
            'ads_banner' => $request->Amazon_ads_banner,
            'ads_inter' => $request->Amazon_ads_inter,
            'ads_reward' => $request->Amazon_ads_reward,
            'ads_native' => $request->Amazon_ads_native,
            'ads_open' => $request->Amazon_ads_open,
            'ads_start' => $request->Amazon_ads_start,
            'ads_banner_huawei' => $request->Amazon_ads_banner_huawei,
            'ads_inter_huawei' => $request->Amazon_ads_inter_huawei,
            'ads_reward_huawei' => $request->Amazon_ads_reward_huawei,
            'ads_native_huawei' => $request->Amazon_ads_native_huawei,
            'ads_splash_huawei' => $request->Amazon_ads_splash_huawei,
            'ads_roll_huawei' => $request->Amazon_ads_roll_huawei,
        ];
        $Amazon_ads =  json_encode($Amazon_ads);

        $Samsung_ads = [
            'ads_id' => $request->Samsung_ads_id,
            'ads_banner' => $request->Samsung_ads_banner,
            'ads_inter' => $request->Samsung_ads_inter,
            'ads_reward' => $request->Samsung_ads_reward,
            'ads_native' => $request->Samsung_ads_native,
            'ads_open' => $request->Samsung_ads_open,
            'ads_start' => $request->Samsung_ads_start,
            'ads_banner_huawei' => $request->Samsung_ads_banner_huawei,
            'ads_inter_huawei' => $request->Samsung_ads_inter_huawei,
            'ads_reward_huawei' => $request->Samsung_ads_reward_huawei,
            'ads_native_huawei' => $request->Samsung_ads_native_huawei,
            'ads_splash_huawei' => $request->Samsung_ads_splash_huawei,
            'ads_roll_huawei' => $request->Samsung_ads_roll_huawei,
        ];
        $Samsung_ads =  json_encode($Samsung_ads);

        $Xiaomi_ads = [
            'ads_id' => $request->Xiaomi_ads_id,
            'ads_banner' => $request->Xiaomi_ads_banner,
            'ads_inter' => $request->Xiaomi_ads_inter,
            'ads_reward' => $request->Xiaomi_ads_reward,
            'ads_native' => $request->Xiaomi_ads_native,
            'ads_open' => $request->Xiaomi_ads_open,
            'ads_start' => $request->Xiaomi_ads_start,
            'ads_banner_huawei' => $request->Xiaomi_ads_banner_huawei,
            'ads_inter_huawei' => $request->Xiaomi_ads_inter_huawei,
            'ads_reward_huawei' => $request->Xiaomi_ads_reward_huawei,
            'ads_native_huawei' => $request->Xiaomi_ads_native_huawei,
            'ads_splash_huawei' => $request->Xiaomi_ads_splash_huawei,
            'ads_roll_huawei' => $request->Xiaomi_ads_roll_huawei,
        ];
        $Xiaomi_ads =  json_encode($Xiaomi_ads);

        $Oppo_ads = [
            'ads_id' => $request->Oppo_ads_id,
            'ads_banner' => $request->Oppo_ads_banner,
            'ads_inter' => $request->Oppo_ads_inter,
            'ads_reward' => $request->Oppo_ads_reward,
            'ads_native' => $request->Oppo_ads_native,
            'ads_open' => $request->Oppo_ads_open,
            'ads_start' => $request->Oppo_ads_start,
            'ads_banner_huawei' => $request->Oppo_ads_banner_huawei,
            'ads_inter_huawei' => $request->Oppo_ads_inter_huawei,
            'ads_reward_huawei' => $request->Oppo_ads_reward_huawei,
            'ads_native_huawei' => $request->Oppo_ads_native_huawei,
            'ads_splash_huawei' => $request->Oppo_ads_splash_huawei,
            'ads_roll_huawei' => $request->Oppo_ads_roll_huawei,
        ];
        $Oppo_ads =  json_encode($Oppo_ads);

        $Vivo_ads = [
            'ads_id' => $request->Vivo_ads_id,
            'ads_banner' => $request->Vivo_ads_banner,
            'ads_inter' => $request->Vivo_ads_inter,
            'ads_reward' => $request->Vivo_ads_reward,
            'ads_native' => $request->Vivo_ads_native,
            'ads_open' => $request->Vivo_ads_open,
            'ads_start' => $request->Vivo_ads_start,
            'ads_banner_huawei' => $request->Vivo_ads_banner_huawei,
            'ads_inter_huawei' => $request->Vivo_ads_inter_huawei,
            'ads_reward_huawei' => $request->Vivo_ads_reward_huawei,
            'ads_native_huawei' => $request->Vivo_ads_native_huawei,
            'ads_splash_huawei' => $request->Vivo_ads_splash_huawei,
            'ads_roll_huawei' => $request->Vivo_ads_roll_huawei,
        ];
        $Vivo_ads =  json_encode($Vivo_ads);

        $Huawei_ads = [
            'ads_id' => $request->Huawei_ads_id,
            'ads_banner' => $request->Huawei_ads_banner,
            'ads_inter' => $request->Huawei_ads_inter,
            'ads_reward' => $request->Huawei_ads_reward,
            'ads_native' => $request->Huawei_ads_native,
            'ads_open' => $request->Huawei_ads_open,
            'ads_start' => $request->Huawei_ads_start,
            'ads_banner_huawei' => $request->Huawei_ads_banner_huawei,
            'ads_inter_huawei' => $request->Huawei_ads_inter_huawei,
            'ads_reward_huawei' => $request->Huawei_ads_reward_huawei,
            'ads_native_huawei' => $request->Huawei_ads_native_huawei,
            'ads_splash_huawei' => $request->Huawei_ads_splash_huawei,
            'ads_roll_huawei' => $request->Huawei_ads_roll_huawei,
        ];
        $Huawei_ads =  json_encode($Huawei_ads);

        $data = new ProjectModel();
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
        $data['link_store_vietmmo'] = $request->link_store_vietmmo;
        $data['buildinfo_mess'] = 'Trạng thái tĩnh';

        $data['Chplay_package'] = $request->Chplay_package;
        $data['Chplay_buildinfo_store_name_x'] = $request->Chplay_buildinfo_store_name_x;
        $data['Chplay_buildinfo_link_store'] = $request->Chplay_buildinfo_link_store;
        $data['Chplay_buildinfo_email_dev_x'] = $request->Chplay_buildinfo_email_dev_x;
        $data['Chplay_buildinfo_link_app'] = $request->Chplay_buildinfo_link_app;
        $data['Chplay_policy'] = $request->Chplay_policy;
        $data['Chplay_keystore_profile'] = $request->Chplay_keystore_profile;
        $data['Chplay_ads'] = $Chplay_ads;
        $data['Chplay_sdk'] = $request->Chplay_sdk;
        $data['Chplay_status'] = 0;

        $data['Amazon_package'] = $request->Amazon_package;
        $data['Amazon_buildinfo_store_name_x'] = $request->Amazon_buildinfo_store_name_x;
        $data['Amazon_buildinfo_link_store'] = $request->Amazon_buildinfo_link_store;
        $data['Amazon_buildinfo_email_dev_x'] = $request->Amazon_buildinfo_email_dev_x;
        $data['Amazon_buildinfo_link_app'] = $request->Amazon_buildinfo_link_app;
        $data['Amazon_policy'] = $request->Amazon_policy;
        $data['Amazon_keystore_profile'] = $request->Amazon_keystore_profile;
        $data['Amazon_ads'] = $Amazon_ads;
        $data['Amazon_sdk'] = $request->Amazon_sdk;
        $data['Amazon_status'] = 0;

        $data['Samsung_package'] = $request->Samsung_package;
        $data['Samsung_buildinfo_store_name_x'] = $request->Samsung_buildinfo_store_name_x;
        $data['Samsung_buildinfo_link_store'] = $request->Samsung_buildinfo_link_store;
        $data['Samsung_buildinfo_email_dev_x'] = $request->Samsung_buildinfo_email_dev_x;
        $data['Samsung_buildinfo_link_app'] = $request->Samsung_buildinfo_link_app;
        $data['Samsung_keystore_profile'] = $request->Samsung_keystore_profile;
        $data['Samsung_policy'] = $request->Samsung_policy;
        $data['Samsung_ads'] = $Samsung_ads;
        $data['Samsung_sdk'] = $request->Samsung_sdk;
        $data['Samsung_status'] = 0;

        $data['Xiaomi_package'] = $request->Xiaomi_package;
        $data['Xiaomi_buildinfo_store_name_x'] = $request->Xiaomi_buildinfo_store_name_x;
        $data['Xiaomi_buildinfo_link_store'] = $request->Xiaomi_buildinfo_link_store;
        $data['Xiaomi_buildinfo_email_dev_x'] = $request->Xiaomi_buildinfo_email_dev_x;
        $data['Xiaomi_buildinfo_link_app'] = $request->Xiaomi_buildinfo_link_app;
        $data['Xiaomi_policy'] = $request->Xiaomi_policy;
        $data['Xiaomi_keystore_profile'] = $request->Xiaomi_keystore_profile;
        $data['Xiaomi_ads'] = $Xiaomi_ads;
        $data['Xiaomi_sdk'] = $request->Xiaomi_sdk;
        $data['Xiaomi_status'] = 0;

        $data['Oppo_package'] = $request->Oppo_package;
        $data['Oppo_buildinfo_store_name_x'] = $request->Oppo_buildinfo_store_name_x;
        $data['Oppo_buildinfo_link_store'] = $request->Oppo_buildinfo_link_store;
        $data['Oppo_buildinfo_email_dev_x'] = $request->Oppo_buildinfo_email_dev_x;
        $data['Oppo_buildinfo_link_app'] = $request->Oppo_buildinfo_link_app;
        $data['Oppo_policy'] = $request->Oppo_policy;
        $data['Oppo_keystore_profile'] = $request->Oppo_keystore_profile;
        $data['Oppo_ads'] = $Oppo_ads;
        $data['Oppo_sdk'] = $request->Oppo_sdk;
        $data['Oppo_status'] = 0;

        $data['Vivo_package'] = $request->Vivo_package;
        $data['Vivo_buildinfo_store_name_x'] = $request->Vivo_buildinfo_store_name_x;
        $data['Vivo_buildinfo_link_store'] = $request->Vivo_buildinfo_link_store;
        $data['Vivo_buildinfo_email_dev_x'] = $request->Vivo_buildinfo_email_dev_x;
        $data['Vivo_buildinfo_link_app'] = $request->Vivo_buildinfo_link_app;
        $data['Vivo_policy'] = $request->Vivo_policy;
        $data['Vivo_keystore_profile'] = $request->Vivo_keystore_profile;
        $data['Vivo_ads'] = $Vivo_ads;
        $data['Vivo_sdk'] = $request->Vivo_sdk;
        $data['Vivo_status'] = 100;

        $data['Huawei_package'] = $request->Huawei_package;
        $data['Huawei_appId'] = $request->Huawei_appId;
        $data['Huawei_buildinfo_store_name_x'] = $request->Huawei_buildinfo_store_name_x;
        $data['Huawei_buildinfo_link_store'] = $request->Huawei_buildinfo_link_store;
        $data['Huawei_buildinfo_email_dev_x'] = $request->Huawei_buildinfo_email_dev_x;
        $data['Huawei_buildinfo_link_app'] = $request->Huawei_buildinfo_link_app;
        $data['Huawei_policy'] = $request->Huawei_policy;
        $data['Huawei_keystore_profile'] = $request->Huawei_keystore_profile;
        $data['Huawei_ads'] = $Huawei_ads;
        $data['Huawei_sdk'] = $request->Huawei_sdk;
        $data['Huawei_status'] = 100;
        $data['Huawei_bot'] = json_encode(['time_bot'=>Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString()]);

        $data['data_onoff'] = $request->data_status ? (int)  $request->data_status :0;

//        if(isset($request->Online) || isset($request->Online) ){
//            dd(1)
//        }

        if(isset($request->logo)){
            $du_an = preg_split("/[-]+/",$request->projectname) ? preg_split("/[-]+/",$request->projectname)[0] : 'DA';
            $path_logo = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
            if (!file_exists($path_logo)) {
                mkdir($path_logo, 777, true);
            }
            $file = $request->file('logo');
            $img = Image::make($file->path());
            $img->resize(512, 512)
                ->save($path_logo.'lg.png',85);
            $img->resize(114, 114)
                ->save($path_logo.'lg114.png',85);
            $data['logo'] = 'lg.png';
        }



        if($request->project_file){
            $du_an = preg_split("/[-]+/",$request->projectname) ? preg_split("/[-]+/",$request->projectname)[0] : 'DA';
            $destinationPath = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->project_file;
            $extension = $file->getClientOriginalExtension();
            $file_name = 'DATA'.'.'.$extension;
            $data['project_file'] = $file_name;
            $file->move($destinationPath, $file_name);
        }
        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id)
    {
        $policy = '';
        $project = ProjectModel::where('projectid',$id)->first();
        $policy = Template::select('policy1','policy2')->where('id',$project->template)->first();
        $store_name = Dev::with('ga')->select('store_name','id_ga')->where('id',$project->Chplay_buildinfo_store_name_x)->first();
        $da= Da::select('ma_da')->where('id',$project->ma_da)->first();
        $keystore= Keystore::where('name_keystore',$project->buildinfo_keystore)->first();


        $keystore_chplay= Keystore::where('name_keystore',$project->Chplay_keystore_profile)->first();
        $keystore_amazon= Keystore::where('name_keystore',$project->Amazon_keystore_profile)->first();
        $keystore_samsung= Keystore::where('name_keystore',$project->Samsung_keystore_profile)->first();
        $keystore_xiaomi= Keystore::where('name_keystore',$project->Xiaomi_keystore_profile)->first();
        $keystore_oppo= Keystore::where('name_keystore',$project->Oppo_keystore_profile)->first();
        $keystore_vivo= Keystore::where('name_keystore',$project->Vivo_keystore_profile)->first();
        $keystore_huawei= Keystore::where('name_keystore',$project->Huawei_keystore_profile)->first();

        $template= Template::select('template','package','ads','Chplay_category','Amazon_category','Samsung_category','Xiaomi_category','Oppo_category','Vivo_category','Huawei_category')->where('id',$project->template)->first();
        $store_name_amazon= Dev_Amazon::with('ga')->select('amazon_store_name','amazon_ga_name')->where('id',$project->Amazon_buildinfo_store_name_x)->first();
        $store_name_samsung= Dev_Samsung::with('ga')->select('samsung_store_name','samsung_ga_name')->where('id',$project->Samsung_buildinfo_store_name_x)->first();
        $store_name_xiaomi= Dev_Xiaomi::with('ga')->select('xiaomi_store_name','xiaomi_ga_name')->where('id',$project->Xiaomi_buildinfo_store_name_x)->first();
        $store_name_oppo= Dev_Oppo::with('ga')->select('oppo_store_name','oppo_ga_name')->where('id',$project->Oppo_buildinfo_store_name_x)->first();
        $store_name_vivo= Dev_Vivo::with('ga')->select('vivo_store_name','vivo_ga_name')->where('id',$project->Vivo_buildinfo_store_name_x)->first();
        $store_name_huawei= Dev_Huawei::with('ga')->select('huawei_store_name','huawei_ga_name')->where('id',$project->Huawei_buildinfo_store_name_x)->first();
        $ga_name_amazon = 'Chưa có';
        $ga_name_chplay = 'Chưa có';
        $ga_name_samsung = 'Chưa có';
        $ga_name_xiaomi = 'Chưa có';
        $ga_name_oppo = 'Chưa có';
        $ga_name_vivo = 'Chưa có';
        $ga_name_huawei = 'Chưa có';


        if(isset($store_name) && $store_name->ga){
                $ga_name_chplay =$store_name->ga_name;
        }else{
            $ga_name_chplay = 'Chưa có';
        }

        if(isset($store_name_amazon) && $store_name_amazon->ga){
            $ga_name_amazon =$store_name_amazon->ga->ga_name;
        }else{
            $ga_name_amazon = 'Chưa có';
        }

        if(isset($store_name_samsung) && $store_name_samsung->ga){
            $ga_name_samsung =$store_name_samsung->ga->ga_name;
        }else{
            $ga_name_samsung = 'Chưa có';
        }

        if(isset($store_name_xiaomi) && $store_name_xiaomi->ga){
            $ga_name_xiaomi =$store_name_xiaomi->ga->ga_name;
        }else{
            $ga_name_xiaomi = 'Chưa có';
        }

        if(isset($store_name_oppo) && $store_name_oppo->ga){
            $ga_name_oppo =$store_name_oppo->ga->ga_name;
        }else{
            $ga_name_oppo = 'Chưa có';
        }

        if(isset($store_name_vivo) && $store_name_vivo->ga){
            $ga_name_vivo =$store_name_vivo->ga->ga_name;
        }else{
            $ga_name_vivo = 'Chưa có';
        }

        if(isset($store_name_huawei) && $store_name_huawei->ga){
            $ga_name_huawei =$store_name_huawei->ga->ga_name;
        }else{
            $ga_name_huawei= 'Chưa có';
        }


        return response()->json([$project,$policy,$store_name,$da,$template,
            $store_name_amazon,$store_name_samsung,$store_name_xiaomi,$store_name_oppo,$store_name_vivo,
            $ga_name_chplay,$ga_name_amazon,$ga_name_samsung,$ga_name_xiaomi,$ga_name_oppo,$ga_name_vivo,
            $store_name_huawei,$ga_name_huawei,
            $keystore,$keystore_chplay,$keystore_amazon,$keystore_samsung,$keystore_xiaomi,$keystore_oppo,$keystore_vivo,$keystore_huawei
        ]);
    }

    public function fake($id)
    {
        $project = ProjectModel::find($id);
        return response()->json($project);
    }

    public function getInfofake(Request $request){
        $result = $request->all();
        if($request->action == 'dashboard'){
            return view('fake_dashboard',compact('result'));
        }
        if($request->action == 'app'){
            return view('fake_app',compact('result'));
        }



    }






    public function editDesEN($id)
    {
        $project = ProjectModel::select('projectid','des_en','summary_en','title_app')->where('projectid',$id)->first();
        return response()->json($project);
    }
    public function editDesVN($id)
    {
        $project = ProjectModel::select('projectid','des_vn','summary_vn','title_app')->where('projectid',$id)->first();
        return response()->json($project);
    }
    public function update(Request $request)
    {
        $id = $request->project_id;
        $rules = [
            'projectname' =>'unique:ngocphandang_project,projectname,'.$id.',projectid',
            'ma_da' => 'required',
            'template' => 'required',
            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
//            'logo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'project_file' => 'mimes:zip',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
            'ma_da.required'=>'Mã dự án không để trống',
            'template.required'=>'Mã template không để trống',
            'title_app.required'=>'Tiêu đề ứng không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'project_file.mimes'=>'*.zip',
//            'logo.mimes'=>'Logo không đúng định dạng: jpeg, png, jpg, gif, svg.',
//            'logo.max'=>'Logo max: 2M.',
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
            'ads_open' => $request->Chplay_ads_open,
            'ads_start' => $request->Chplay_ads_start,
            'ads_banner_huawei' => $request->Chplay_ads_banner_huawei,
            'ads_inter_huawei' => $request->Chplay_ads_inter_huawei,
            'ads_reward_huawei' => $request->Chplay_ads_reward_huawei,
            'ads_native_huawei' => $request->Chplay_ads_native_huawei,
            'ads_splash_huawei' => $request->Chplay_ads_splash_huawei,
            'ads_roll_huawei' => $request->Chplay_ads_roll_huawei,
        ];
        $Chplay_ads =  json_encode($Chplay_ads);

        $Amazon_ads = [
            'ads_id' => $request->Amazon_ads_id,
            'ads_banner' => $request->Amazon_ads_banner,
            'ads_inter' => $request->Amazon_ads_inter,
            'ads_reward' => $request->Amazon_ads_reward,
            'ads_native' => $request->Amazon_ads_native,
            'ads_open' => $request->Amazon_ads_open,
            'ads_start' => $request->Amazon_ads_start,
            'ads_banner_huawei' => $request->Amazon_ads_banner_huawei,
            'ads_inter_huawei' => $request->Amazon_ads_inter_huawei,
            'ads_reward_huawei' => $request->Amazon_ads_reward_huawei,
            'ads_native_huawei' => $request->Amazon_ads_native_huawei,
            'ads_splash_huawei' => $request->Amazon_ads_splash_huawei,
            'ads_roll_huawei' => $request->Amazon_ads_roll_huawei,
        ];
        $Amazon_ads =  json_encode($Amazon_ads);

        $Samsung_ads = [
            'ads_id' => $request->Samsung_ads_id,
            'ads_banner' => $request->Samsung_ads_banner,
            'ads_inter' => $request->Samsung_ads_inter,
            'ads_reward' => $request->Samsung_ads_reward,
            'ads_native' => $request->Samsung_ads_native,
            'ads_open' => $request->Samsung_ads_open,
            'ads_start' => $request->Samsung_ads_start,
            'ads_banner_huawei' => $request->Samsung_ads_banner_huawei,
            'ads_inter_huawei' => $request->Samsung_ads_inter_huawei,
            'ads_reward_huawei' => $request->Samsung_ads_reward_huawei,
            'ads_native_huawei' => $request->Samsung_ads_native_huawei,
            'ads_splash_huawei' => $request->Samsung_ads_splash_huawei,
            'ads_roll_huawei' => $request->Samsung_ads_roll_huawei,
        ];
        $Samsung_ads =  json_encode($Samsung_ads);

        $Xiaomi_ads = [
            'ads_id' => $request->Xiaomi_ads_id,
            'ads_banner' => $request->Xiaomi_ads_banner,
            'ads_inter' => $request->Xiaomi_ads_inter,
            'ads_reward' => $request->Xiaomi_ads_reward,
            'ads_native' => $request->Xiaomi_ads_native,
            'ads_open' => $request->Xiaomi_ads_open,
            'ads_start' => $request->Xiaomi_ads_start,
            'ads_banner_huawei' => $request->Xiaomi_ads_banner_huawei,
            'ads_inter_huawei' => $request->Xiaomi_ads_inter_huawei,
            'ads_reward_huawei' => $request->Xiaomi_ads_reward_huawei,
            'ads_native_huawei' => $request->Xiaomi_ads_native_huawei,
            'ads_splash_huawei' => $request->Xiaomi_ads_splash_huawei,
            'ads_roll_huawei' => $request->Xiaomi_ads_roll_huawei,
        ];
        $Xiaomi_ads =  json_encode($Xiaomi_ads);

        $Oppo_ads = [
            'ads_id' => $request->Oppo_ads_id,
            'ads_banner' => $request->Oppo_ads_banner,
            'ads_inter' => $request->Oppo_ads_inter,
            'ads_reward' => $request->Oppo_ads_reward,
            'ads_native' => $request->Oppo_ads_native,
            'ads_open' => $request->Oppo_ads_open,
            'ads_start' => $request->Oppo_ads_start,
            'ads_banner_huawei' => $request->Oppo_ads_banner_huawei,
            'ads_inter_huawei' => $request->Oppo_ads_inter_huawei,
            'ads_reward_huawei' => $request->Oppo_ads_reward_huawei,
            'ads_native_huawei' => $request->Oppo_ads_native_huawei,
            'ads_splash_huawei' => $request->Oppo_ads_splash_huawei,
            'ads_roll_huawei' => $request->Oppo_ads_roll_huawei,
        ];
        $Oppo_ads =  json_encode($Oppo_ads);

        $Vivo_ads = [
            'ads_id' => $request->Vivo_ads_id,
            'ads_banner' => $request->Vivo_ads_banner,
            'ads_inter' => $request->Vivo_ads_inter,
            'ads_reward' => $request->Vivo_ads_reward,
            'ads_native' => $request->Vivo_ads_native,
            'ads_open' => $request->Vivo_ads_open,
            'ads_start' => $request->Vivo_ads_start,
            'ads_banner_huawei' => $request->Vivo_ads_banner_huawei,
            'ads_inter_huawei' => $request->Vivo_ads_inter_huawei,
            'ads_reward_huawei' => $request->Vivo_ads_reward_huawei,
            'ads_native_huawei' => $request->Vivo_ads_native_huawei,
            'ads_splash_huawei' => $request->Vivo_ads_splash_huawei,
            'ads_roll_huawei' => $request->Vivo_ads_roll_huawei,
        ];
        $Vivo_ads =  json_encode($Vivo_ads);

        $Huawei_ads = [
            'ads_id' => $request->Huawei_ads_id,
            'ads_banner' => $request->Huawei_ads_banner,
            'ads_inter' => $request->Huawei_ads_inter,
            'ads_reward' => $request->Huawei_ads_reward,
            'ads_native' => $request->Huawei_ads_native,
            'ads_open' => $request->Huawei_ads_open,
            'ads_start' => $request->Huawei_ads_start,
            'ads_banner_huawei' => $request->Huawei_ads_banner_huawei,
            'ads_inter_huawei' => $request->Huawei_ads_inter_huawei,
            'ads_reward_huawei' => $request->Huawei_ads_reward_huawei,
            'ads_native_huawei' => $request->Huawei_ads_native_huawei,
            'ads_splash_huawei' => $request->Huawei_ads_splash_huawei,
            'ads_roll_huawei' => $request->Huawei_ads_roll_huawei,
        ];
        $Huawei_ads =  json_encode($Huawei_ads);
        $data = ProjectModel::find($id);
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
        $data->link_store_vietmmo = $request->link_store_vietmmo;

        $data->Chplay_package = $request->Chplay_package;
        $data->Chplay_buildinfo_store_name_x = $request->Chplay_buildinfo_store_name_x;
        $data->Chplay_buildinfo_link_store = $request->Chplay_buildinfo_link_store;
        $data->Chplay_buildinfo_link_app = $request->Chplay_buildinfo_link_app;
        $data->Chplay_buildinfo_email_dev_x = $request->Chplay_buildinfo_email_dev_x;
        $data->Chplay_ads = $Chplay_ads;
        $data->update(['Chplay_bot->log_status' => $data->Chplay_status]);
//        $data->Chplay_bot->log_status = $data->Chplay_bot->log_status.'|'.$data->Chplay_status;
        $data->Chplay_status = $request->Chplay_status;
        $data->Chplay_policy = $request->Chplay_policy;
        $data->Chplay_sdk = $request->Chplay_sdk;
        $data->Chplay_keystore_profile = $request->Chplay_keystore_profile;

        $data->Amazon_package = $request->Amazon_package;
        $data->Amazon_buildinfo_store_name_x = $request->Amazon_buildinfo_store_name_x;
        $data->Amazon_buildinfo_link_store = $request->Amazon_buildinfo_link_store;
        $data->Amazon_buildinfo_link_app = $request->Amazon_buildinfo_link_app;
        $data->Amazon_buildinfo_email_dev_x = $request->Amazon_buildinfo_email_dev_x;
        $data->Amazon_ads = $Amazon_ads;
        $data->Amazon_status = $request->Amazon_status;
        $data->Amazon_policy = $request->Amazon_policy;
        $data->Amazon_sdk = $request->Amazon_sdk;
        $data->Amazon_keystore_profile = $request->Amazon_keystore_profile;

        $data->Samsung_package = $request->Samsung_package;
        $data->Samsung_buildinfo_store_name_x = $request->Samsung_buildinfo_store_name_x;
        $data->Samsung_buildinfo_link_store = $request->Samsung_buildinfo_link_store;
        $data->Samsung_buildinfo_link_app = $request->Samsung_buildinfo_link_app;
        $data->Samsung_buildinfo_email_dev_x = $request->Samsung_buildinfo_email_dev_x;
        $data->Samsung_ads = $Samsung_ads;
        $data->Samsung_status = $request->Samsung_status;
        $data->Samsung_policy = $request->Samsung_policy;
        $data->Samsung_sdk = $request->Samsung_sdk;
        $data->Samsung_keystore_profile = $request->Samsung_keystore_profile;

        $data->Xiaomi_package = $request->Xiaomi_package;
        $data->Xiaomi_buildinfo_store_name_x = $request->Xiaomi_buildinfo_store_name_x;
        $data->Xiaomi_buildinfo_link_store = $request->Xiaomi_buildinfo_link_store;
        $data->Xiaomi_buildinfo_link_app = $request->Xiaomi_buildinfo_link_app;
        $data->Xiaomi_buildinfo_email_dev_x = $request->Xiaomi_buildinfo_email_dev_x;
        $data->Xiaomi_ads = $Xiaomi_ads;
        $data->Xiaomi_status = $request->Xiaomi_status;
        $data->Xiaomi_policy = $request->Xiaomi_policy;
        $data->Xiaomi_sdk = $request->Xiaomi_sdk;
        $data->Xiaomi_keystore_profile = $request->Xiaomi_keystore_profile;

        $data->Oppo_package = $request->Oppo_package;
        $data->Oppo_buildinfo_store_name_x = $request->Oppo_buildinfo_store_name_x;
        $data->Oppo_buildinfo_link_store = $request->Oppo_buildinfo_link_store;
        $data->Oppo_buildinfo_link_app = $request->Oppo_buildinfo_link_app;
        $data->Oppo_buildinfo_email_dev_x = $request->Oppo_buildinfo_email_dev_x;
        $data->Oppo_ads = $Oppo_ads;
        $data->Oppo_status = $request->Oppo_status;
        $data->Oppo_policy = $request->Oppo_policy;
        $data->Oppo_sdk = $request->Oppo_sdk;
        $data->Oppo_keystore_profile = $request->Oppo_keystore_profile;

        $data->Vivo_package = $request->Vivo_package;
        $data->Vivo_buildinfo_store_name_x = $request->Vivo_buildinfo_store_name_x;
        $data->Vivo_buildinfo_link_store = $request->Vivo_buildinfo_link_store;
        $data->Vivo_buildinfo_link_app = $request->Vivo_buildinfo_link_app;
        $data->Vivo_buildinfo_email_dev_x = $request->Vivo_buildinfo_email_dev_x;
        $data->Vivo_ads = $Vivo_ads;
        $data->Vivo_status = $request->Vivo_status;
        $data->Vivo_policy = $request->Vivo_policy;
        $data->Vivo_sdk = $request->Vivo_sdk;
        $data->Vivo_keystore_profile = $request->Vivo_keystore_profile;

        $data->Huawei_package = $request->Huawei_package;
        $data->Huawei_appId = $request->Huawei_appId;
        $data->Huawei_buildinfo_store_name_x = $request->Huawei_buildinfo_store_name_x;
        $data->Huawei_buildinfo_link_store = $request->Huawei_buildinfo_link_store;
        $data->Huawei_buildinfo_link_app = $request->Huawei_buildinfo_link_app;
        $data->Huawei_buildinfo_email_dev_x = $request->Huawei_buildinfo_email_dev_x;
        $data->Huawei_ads = $Huawei_ads;
        $data->Huawei_status = $request->Huawei_status;
        $data->Huawei_policy = $request->Huawei_policy;
        $data->Huawei_sdk = $request->Huawei_sdk;
        $data->Huawei_keystore_profile = $request->Huawei_keystore_profile;
        $data->Huawei_bot = $data->Huawei_bot ? : json_encode(['time_bot'=>Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->toDateTimeString()]);

//        $data->data_onoff = $request->data_status ? (int) $request->data_status :0;


//        if($data->logo){
//            if($data->projectname <> $request->projectname){
////                dd($data->project_file);
//                $dir = (public_path('uploads/project/'));
//                rename($dir.$data->projectname, $dir.$request->projectname);
//            }
//        }

//        if($data->project_file){
//            $dir_file = public_path('file-manager/ProjectData/');
//            rename($dir_file.$data->project_file, $dir_file.$request->projectname.'.zip');
//            $data['project_file'] = $request->projectname.'.zip';
//        }

//        if($request->logo){
//            if($data->logo){
//                $path_Remove =  public_path('uploads/project/').$data->projectname;
//                $this->deleteDirectory($path_Remove);
//            }
//            $image = $request->file('logo');
//            $data['logo'] = 'logo_'.time().'.'.$image->extension();
//            $destinationPath = public_path('uploads/project/'.$request->projectname.'/thumbnail/');
//            $img = Image::make($image->path());
//            if (!file_exists($destinationPath)) {
//                mkdir($destinationPath, 777, true);
//            }
//            $img->resize(100, 100, function ($constraint) {
//                $constraint->aspectRatio();
//            })->save($destinationPath.$data['logo']);
//            $destinationPath = public_path('uploads/project/'.$request->projectname);
//            $image->move($destinationPath, $data['logo']);
//        }

        if(isset($request->logo)){
            $du_an = preg_split("/[-]+/",$request->projectname) ? preg_split("/[-]+/",$request->projectname)[0] : 'DA';
            $path_logo = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
            if (!file_exists($path_logo)) {
                mkdir($path_logo, 777, true);
            }
            $file = $request->file('logo');
            $img = Image::make($file->path());
            $img->resize(512, 512)
                ->save($path_logo.'lg.png',85);
            $img->resize(114, 114)
                ->save($path_logo.'lg114.png',85);

            $data->logo = 'lg.png';

        }


        if($request->project_file){
//            if($data->project_file){
//                $path_Remove =  public_path('file-manager/ProjectData/').$data->project_file;
//                if(file_exists($path_Remove)){
//                    unlink($path_Remove);
//                }
//            }
            $du_an = preg_split("/[-]+/",$request->projectname) ? preg_split("/[-]+/",$request->projectname)[0] : 'DA';
            $destinationPath = storage_path('app/public/projects/'.$du_an.'/'.$request->projectname.'/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file = $request->project_file;
            $extension = $file->getClientOriginalExtension();
            $file_name = 'DATA'.'.'.$extension;
            $data->project_file = $file_name;
            $file->move($destinationPath, $file_name);
        }
        $data->projectname = $request->projectname;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function updateQuick(Request $request){
        $id = $request->project_id;
        ProjectModel::updateOrCreate(
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
    public function updatePart(Request $request){
        $id = $request->project_id;
        ProjectModel::updateOrCreate(
            [
                "projectid" => $id,

            ],
            [
                "Chplay_status" => $request->Chplay_status,
                'Amazon_status' => $request->Amazon_status,
                'Samsung_status' => $request->Samsung_status,
                'Xiaomi_status' => $request->Xiaomi_status,
                'Oppo_status' => $request->Oppo_status,
                'Vivo_status' => $request->Vivo_status,
                'Huawei_status' => $request->Huawei_status,
            ]);
        return response()->json(['success'=>'Cập nhật thành công']);


    }
    public function updateDesEN(Request $request){
        $id = $request->project_id;
        ProjectModel::updateOrCreate(
            [
                "projectid" => $id,

            ],
            [
                "des_en" => $request->des_en,
                "summary_en" => $request->summary_en,
                "title_app" => $request->title_app,
            ]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function updateDesVN(Request $request){
        $id = $request->project_id;
        ProjectModel::updateOrCreate(
            [
                "projectid" => $id,

            ],
            [
                "des_vn" => $request->des_vn,
                "summary_vn" => $request->summary_vn,
                "title_app" => $request->title_app,
            ]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function updateStatus(Request $request){
        $data = ProjectModel::where('projectid',$request->project_id)->first();
        ProjectModel::updateOrCreate(
            [
                "projectid" => $request->project_id,

            ],
            [
                "Chplay_status" => $request->Chplay_status,
                'Chplay_bot->log_status' => $data->Chplay_status,
            ]);
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $project = ProjectModel::find($id);
        if($project->logo){
            $path_image =   public_path('uploads/project/').$project->projectname;
            $this->deleteDirectory($path_image);
        }
        if($project->project_file){
            $path_file  =   public_path('file-manager/ProjectData/').$project->project_file;
            if(file_exists($path_file)){
                unlink($path_file);
            }
        }
        $project->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public function callAction($method, $parameters)
    {
//        $this->AuthLogin();
        return parent::callAction($method, array_values($parameters));
    }
    public function removeProject($id){

        $project = ProjectModel::where('projectid',$id)->first();

        ProjectModel::updateOrCreate(
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
    public function checkbox($id){
        $data = ProjectModel::find($id);
        ProjectModel::updateOrCreate(
            [
                "projectid" => $data->projectid,
            ],
            [
                'Chplay_status' => 7,
                'Chplay_bot->log_status' => $data->Chplay_status,
                'Chplay_bot->installs' => 0,
               'Chplay_bot->numberVoters' => 0,
               'Chplay_bot->numberReviews' => 0,
               'Chplay_bot->score' => 0,
               'Chplay_bot->appVersion' => $data->buildinfo_verstr,
               'Chplay_bot->privacyPoliceUrl' => 0,
               'Chplay_bot->released' => time(),
               'Chplay_bot->updated' => time(),
               'Chplay_bot->logo' =>  '../uploads/project/'.$data->projectname.'/thumbnail/'.$data->logo,
               'Chplay_bot->bot_name_dev' => 0,
            ]
        );
        return response()->json(['success'=> $data->projectname.' đang chờ duyệt']);
    }
    public function select_template(Request $request){
        $template = Template::select('package','ads','Chplay_category','Amazon_category','Samsung_category','Xiaomi_category','Oppo_category','Vivo_category','Huawei_category')->where('id',$request->template)->first();
        return response()->json($template);
    }
    public function checkData($id){
        ProjectModel::where('template',$id)->where('buildinfo_console','<>','2')->Where('buildinfo_console','<>','5')->update(['buildinfo_console'=> 4]);
        return response()->json(['success'=>'Thành công']);
    }
    public function showlog($id){
        $record = ProjectModel::with(['log','da','matemplate'])->where('projectid',$id)->first();
        $logs = $record->log->buildinfo_mess;
        $logs = explode('|',$logs);

        foreach ($logs as $log){

            $data_logs[] =[
                'time' => substr($log,0,$this->substr_Index( $log , 3)),
                'mess' => substr($log, $this->substr_Index( $log , 3) +1)
            ];
        }
        return view('project.showlog',['record'=>$record,'data_logs'=>$data_logs]);
    }
    public function substr_Index( $str, $nth ){
        $str2 = '';
        $posTotal = 0;
        for($i=0; $i < $nth; $i++){
            if($str2 != ''){
                $str = $str2;
            }
            $pos   = strpos($str, ':');
            $str2  = substr($str, $pos+1);
            $posTotal += $pos+1;
        }
        return $posTotal-1;
    }
    public function select_store_name_chplay(Request $request){
        $dev_name_chplay = DB::table('ngocphandang_dev')
            ->where('ngocphandang_dev.id',$request->store_name)
            ->first();
        if($dev_name_chplay->id_ga != null){
            $ga_name_chplay = DB::table('ngocphandang_dev')
                ->join('ngocphandang_ga','ngocphandang_dev.id_ga','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev.id_ga',$dev_name_chplay->id_ga)
                ->first();
            $ga_name_chplay =$ga_name_chplay->ga_name;
        }else{
            $ga_name_chplay = 'Chưa có';
        }
        return response()->json([$dev_name_chplay->dev_name,$ga_name_chplay]);
    }
    public function select_store_name_amazon(Request $request){
        $dev_name_amazon = DB::table('ngocphandang_dev_amazon')
            ->where('ngocphandang_dev_amazon.id',$request->store_name)
            ->first();
        if($dev_name_amazon->amazon_ga_name != null){
            $ga_name_amazon = DB::table('ngocphandang_dev_amazon')
                ->join('ngocphandang_ga','ngocphandang_dev_amazon.amazon_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_amazon.amazon_ga_name',$dev_name_amazon->amazon_ga_name)
                ->first();
            $ga_name_amazon =$ga_name_amazon->ga_name;
        }else{
            $ga_name_amazon = 'Chưa có';
        }
        return response()->json([$dev_name_amazon->amazon_dev_name,$ga_name_amazon]);
    }
    public function select_store_name_samsung(Request $request){
        $dev_name_samsung = DB::table('ngocphandang_dev_samsung')
            ->where('ngocphandang_dev_samsung.id',$request->store_name)
            ->first();
        if($dev_name_samsung->	samsung_ga_name != null){
            $ga_name_samsung = DB::table('ngocphandang_dev_samsung')
                ->join('ngocphandang_ga','ngocphandang_dev_samsung.samsung_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_samsung.samsung_ga_name',$dev_name_samsung->samsung_ga_name)
                ->first();
            $ga_name_samsung =$ga_name_samsung->ga_name;
        }else{
            $ga_name_samsung = 'Chưa có';
        }
        return response()->json([$dev_name_samsung->samsung_dev_name,$ga_name_samsung]);
    }
    public function select_store_name_xiaomi(Request $request){
        $dev_name_xiaomi = DB::table('ngocphandang_dev_xiaomi')
            ->where('ngocphandang_dev_xiaomi.id',$request->store_name)
            ->first();

        if($dev_name_xiaomi->xiaomi_ga_name != null){
            $ga_name_xiaomi = DB::table('ngocphandang_dev_xiaomi')
                ->join('ngocphandang_ga','ngocphandang_dev_xiaomi.xiaomi_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_xiaomi.xiaomi_ga_name',$dev_name_xiaomi->xiaomi_ga_name)
                ->first();
            $ga_name_xiaomi =$ga_name_xiaomi->ga_name;
        }else{
            $ga_name_xiaomi = 'Chưa có';
        }
        return response()->json([$dev_name_xiaomi->xiaomi_dev_name,$ga_name_xiaomi]);
    }
    public function select_store_name_oppo(Request $request){
        $dev_name_oppo = DB::table('ngocphandang_dev_oppo')
            ->where('ngocphandang_dev_oppo.id',$request->store_name)
            ->first();
        if($dev_name_oppo->	oppo_ga_name != null){
            $ga_name_oppo = DB::table('ngocphandang_dev_oppo')
                ->join('ngocphandang_ga','ngocphandang_dev_oppo.oppo_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_oppo.oppo_ga_name',$dev_name_oppo->oppo_ga_name)
                ->first();
            $ga_name_oppo =$ga_name_oppo->ga_name;
        }else{
            $ga_name_oppo = 'Chưa có';
        }
        return response()->json([$dev_name_oppo->oppo_dev_name,$ga_name_oppo]);
    }
    public function select_store_name_vivo(Request $request){
        $dev_name_vivo = DB::table('ngocphandang_dev_vivo')
            ->where('ngocphandang_dev_vivo.id',$request->store_name)
            ->first();
        if($dev_name_vivo->	vivo_ga_name != null){
            $ga_name_vivo = DB::table('ngocphandang_dev_vivo')
                ->join('ngocphandang_ga','ngocphandang_dev_vivo.vivo_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_vivo.vivo_ga_name',$dev_name_vivo->vivo_ga_name)
                ->first();
            $ga_name_vivo =$ga_name_vivo->ga_name;
        }else{
            $ga_name_vivo = 'Chưa có';
        }
        return response()->json([$dev_name_vivo->vivo_dev_name,$ga_name_vivo]);
    }
    public function select_store_name_huawei(Request $request){
        $dev_name_huawei = DB::table('ngocphandang_dev_huawei')
            ->where('ngocphandang_dev_huawei.id',$request->store_name)
            ->first();
        if($dev_name_huawei->huawei_ga_name != null){
            $ga_name_huawei = DB::table('ngocphandang_dev_huawei')
                ->join('ngocphandang_ga','ngocphandang_dev_huawei.huawei_ga_name','=','ngocphandang_ga.id')
                ->where('ngocphandang_dev_huawei.huawei_ga_name',$dev_name_huawei->huawei_ga_name)
                ->first();
            if($ga_name_huawei){
                $ga_name_huawei =$ga_name_huawei->ga_name;
            }
        }else{
            $ga_name_huawei = 'Chưa có';
        }
        return response()->json([$dev_name_huawei->huawei_dev_name,$ga_name_huawei]);
    }


    public function select_buildinfo_keystore(Request $request){
        $keystore = $request->buildinfo_keystore;
        $project_id = $request->project_id;
        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
        $project = ProjectModel::where('projectid',$project_id)->first();
        return response()->json([$buildinfo_keystore,$project]);
    }

    public function check_build(Request $request){
//        dd($request->all());
        $project = ProjectModel::select('projectid','projectname','buildinfo_verstr','buildinfo_vernum')->whereIN('projectname',$request->projectname)->get();
//        dd($project);

        return response()->json($project);

    }

    public function updateBuildCheck(Request $request){
        $data = $request->data;
        foreach (array_filter($data) as $item){
            $arr = explode("|",$item);
            ProjectModel::updateOrCreate(
                [
                    "projectname" => $arr[0],
                ],
                [
                    "buildinfo_vernum" => (int)trim($arr[1]),
                    'buildinfo_verstr' => trim($arr[2]),
                    'buildinfo_console' => $request->buildinfo_console,
                    'buildinfo_mess' => 'Chờ xử lý',
                    'time_mess' => time(),
                    'buildinfo_time' =>time(),

                ]);
        }
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function updateDevStatus(Request $request){

        $data = explode("\r\n",$request->project_data);
        $project = ProjectModel::whereIN('projectname',$data)->get();
        foreach ($project as $item){
            ProjectModel::updateOrCreate(
                [
                    "projectname" => $item->projectname,
                ],
                [
                    "Chplay_buildinfo_store_name_x" => $request->Chplay_buildinfo_store_name_x == 0 ? $item->Chplay_buildinfo_store_name_x: $request->Chplay_buildinfo_store_name_x,
                    "Amazon_buildinfo_store_name_x" => $request->Amazon_buildinfo_store_name_x == 0 ? $item->Amazon_buildinfo_store_name_x: $request->Amazon_buildinfo_store_name_x,
                    "Samsung_buildinfo_store_name_x" => $request->Samsung_buildinfo_store_name_x == 0 ? $item->Samsung_buildinfo_store_name_x: $request->Samsung_buildinfo_store_name_x,
                    "Xiaomi_buildinfo_store_name_x" => $request->Xiaomi_buildinfo_store_name_x == 0 ? $item->Xiaomi_buildinfo_store_name_x: $request->Xiaomi_buildinfo_store_name_x,
                    "Oppo_buildinfo_store_name_x" => $request->Oppo_buildinfo_store_name_x == 0 ? $item->Oppo_buildinfo_store_name_x: $request->Oppo_buildinfo_store_name_x,
                    "Vivo_buildinfo_store_name_x" => $request->Vivo_buildinfo_store_name_x == 0 ? $item->Vivo_buildinfo_store_name_x: $request->Vivo_buildinfo_store_name_x,
                    "Huawei_buildinfo_store_name_x" => $request->Huawei_buildinfo_store_name_x == 0 ? $item->Huawei_buildinfo_store_name_x: $request->Huawei_buildinfo_store_name_x,
                    "Chplay_status" => $request->Chplay_status == 0 ? $item->Chplay_status : $request->Chplay_status  ,
                    "Amazon_status" => $request->Amazon_status == 0 ? $item->Amazon_status : $request->Amazon_status  ,
                    "Samsung_status" => $request->Samsung_status == 0 ? $item->Samsung_status : $request->Samsung_status  ,
                    "Xiaomi_status" => $request->Xiaomi_status == 0 ? $item->Xiaomi_status : $request->Xiaomi_status  ,
                    "Oppo_status" => $request->Oppo_status == 0 ? $item->Oppo_status : $request->Oppo_status  ,
                    "Vivo_status" => $request->Vivo_status == 100 ? $item->Vivo_status  : $request->Vivo_status  ,
                    "Huawei_status" => $request->Huawei_status == 100 ? $item->Huawei_status : $request->Huawei_status  ,
                ]);
            }
        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function changeKeystoreMultiple(Request $request){
        $data = explode("\r\n",$request->changeKeystoreMultiple);
        foreach ($data as $item){
            try {
                [$ID_Project, $Key_C, $Key_A, $Key_S, $Key_X, $Key_O, $Key_V, $Key_H ] = explode("|",$item);
                ProjectModel::updateOrCreate(
                    [
                        "projectname" => $ID_Project,
                    ],
                    [
                        "Chplay_keystore_profile" => $Key_C,
                        "Amazon_keystore_profile" => $Key_A,
                        "Samsung_keystore_profile" => $Key_S,
                        "Xiaomi_keystore_profile" => $Key_X,
                        "Vivo_keystore_profile" => $Key_V,
                        "Oppo_keystore_profile" => $Key_O,
                        "Huawei_keystore_profile" => $Key_H,
                    ]);

                Keystore::updateorcreate([
                    'name_keystore' => $Key_C
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_A
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_S
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_X
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_O
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_V
                ]);
                Keystore::updateorcreate([
                    'name_keystore' => $Key_H
                ]);
            }catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error('Message:' . $exception->getMessage() . '--- chang key : ' . $exception->getLine());
            }

        }
        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function changeSdkMultiple(Request $request){
        $data = explode("\r\n",$request->changeSdkMultiple);
        foreach ($data as $item){
            try {
                [$ID_Project, $Sdk_C, $Sdk_A, $Sdk_S, $Sdk_X, $Sdk_O, $Sdk_V, $Sdk_H ] = explode("|",$item);
                ProjectModel::updateOrCreate(
                    [
                        "projectname" => $ID_Project,
                    ],
                    [
                        "Chplay_sdk" =>trim($Sdk_C),
                        "Amazon_sdk" => trim($Sdk_A),
                        "Samsung_sdk" => trim($Sdk_S),
                        "Xiaomi_sdk" => trim($Sdk_X),
                        "Oppo_sdk" =>trim($Sdk_O),
                        "Vivo_sdk" => trim($Sdk_V),
                        "Huawei_sdk" => trim($Sdk_H),
                    ]);

            }catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error('Message:' . $exception->getMessage() . '--- chang sdk : ' . $exception->getLine());
            }

        }
        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function getProject($id){
        $project = ProjectModel::where('projectname',$id)->first();
        if (isset($project)){
            return response()->json(['msg'=>'success','data'=>$project]);
        }else{
            return response()->json(['msg'=>'error']);
        }

    }

    public function show($id){
        $project = ProjectModel::find($id)->load('lang','da','matemplate');
        return view('design_content.upload')->with(compact('project'));
    }


//    public function select_chplay_buildinfo_keystore(Request $request){
//            $keystore = $request->buildinfo_keystore;
//            $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//            return response()->json($buildinfo_keystore);
//    }

//    public function select_amazon_buildinfo_keystore(Request $request){
//        $keystore = $request->buildinfo_keystore;
//        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//        return response()->json($buildinfo_keystore);
//    }
//
//    public function select_chplay_buildinfo_keystore(Request $request){
//        $keystore = $request->buildinfo_keystore;
//        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//        return response()->json($buildinfo_keystore);
//    }
//
//    public function select_chplay_buildinfo_keystore(Request $request){
//        $keystore = $request->buildinfo_keystore;
//        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//        return response()->json($buildinfo_keystore);
//    }
//
//    public function select_chplay_buildinfo_keystore(Request $request){
//        $keystore = $request->buildinfo_keystore;
//        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//        return response()->json($buildinfo_keystore);
//    }
//
//    public function select_chplay_buildinfo_keystore(Request $request){
//        $keystore = $request->buildinfo_keystore;
//        $buildinfo_keystore = Keystore::where('name_keystore',$keystore)->first();
//        return response()->json($buildinfo_keystore);
//    }


    function checkLink($url){
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $headers = get_headers($url,1);
            return stripos($headers[0],"200 OK") ? $url : false;
        } else {
            return false;
        }
    }


    public function convert(Request $request){
        $projects = ProjectModel::all();

        $market = new MakertProject();
        $columns = [
            'market_id',
            'project_id',
            'dev_id',
            'app_name_x' ,
            'package',
            'ads',
            'app_link',
            'policy_link',
            'sdk',
            'keystore',
            'appID',
        ];

        $values = [];

        $action = $request->action;

        switch ($action){
            case 1:
                foreach ($projects as $project){
                    $values [] = [
                        1,
                        $project->projectid,
                        $project->Chplay_buildinfo_store_name_x ?  $project->Chplay_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Chplay_package,
                        $project->Chplay_ads,
                        $project->Chplay_buildinfo_link_app,
                        $project->Chplay_policy,
                        $project->Chplay_sdk,
                        $project->Chplay_keystore_profile,
                        null
                    ];
                }
                break;
            case 2:
                foreach ($projects as $project){
                    $values [] = [
                        2,
                        $project->projectid,
                        $project->Amazon_buildinfo_store_name_x ?  $project->Amazon_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Amazon_package,
                        $project->Amazon_ads,
                        $project->Amazon_buildinfo_link_app,
                        $project->Amazon_policy,
                        $project->Amazon_sdk,
                        $project->Amazon_keystore_profile,
                        null
                    ];
                }
                break;
            case 3:
                foreach ($projects as $project){
                    $values [] = [
                        3,
                        $project->projectid,
                        $project->Samsung_buildinfo_store_name_x ?  $project->Samsung_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Samsung_package,
                        $project->Samsung_ads,
                        $project->Samsung_buildinfo_link_app,
                        $project->Samsung_policy,
                        $project->Samsung_sdk,
                        $project->Samsung_keystore_profile,
                        null
                    ];
                }
                break;
            case 4:
                foreach ($projects as $project){
                    $values [] = [
                        4,
                        $project->projectid,
                        $project->Xiaomi_buildinfo_store_name_x ?  $project->Xiaomi_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Xiaomi_package,
                        $project->Xiaomi_ads,
                        $project->Xiaomi_buildinfo_link_app,
                        $project->Xiaomi_policy,
                        $project->Xiaomi_sdk,
                        $project->Xiaomi_keystore_profile,
                        null
                    ];
                }
                break;
            case 5:
                foreach ($projects as $project){
                    $values [] = [
                        5,
                        $project->projectid,
                        $project->Oppo_buildinfo_store_name_x ?  $project->Oppo_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Oppo_package,
                        $project->Oppo_ads,
                        $project->Oppo_buildinfo_link_app,
                        $project->Oppo_policy,
                        $project->Oppo_sdk,
                        $project->Oppo_keystore_profile,
                        null
                    ];
                }
                break;
            case 6:
                foreach ($projects as $project){
                    $values [] = [
                        6,
                        $project->projectid,
                        $project->Vivo_buildinfo_store_name_x ?  $project->Vivo_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Vivo_package,
                        $project->Vivo_ads,
                        $project->Vivo_buildinfo_link_app,
                        $project->Vivo_policy,
                        $project->Vivo_sdk,
                        $project->Vivo_keystore_profile,
                        null
                    ];
                }
                break;
            case 7:
                foreach ($projects as $project){
                    $values [] = [
                        7,
                        $project->projectid,
                        $project->Huawei_buildinfo_store_name_x ?  $project->Huawei_buildinfo_store_name_x :0,
                        $project->buildinfo_app_name_x,
                        $project->Huawei_package,
                        $project->Huawei_ads,
                        $project->Huawei_buildinfo_link_app,
                        $project->Huawei_policy,
                        $project->Huawei_sdk,
                        $project->Huawei_keystore_profile,
                        $project->Huawei_appId,
                    ];
                }
                break;
        }
        $batchSize = 500;
        $result = batch()->insert($market,$columns, $values,$batchSize);
        dd($result );
//        Batch::update($market, $insert, $index);


    }


}
