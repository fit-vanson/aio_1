<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class Project_Controller extends Controller
{
    public function index(Request $request)
    {
        $header = [
            'title' => 'Project',
            'button' => [
                'Create'            => 'createNewProject',
                'Build And Check'   => 'buildandcheck',
                'Status'            => 'dev_status',
                'KeyStore'          => 'change_keystore',
                'SDK'          => 'change_sdk',
            ]
        ];
        return view('project.index')->with(compact('header'));
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


        $totalRecords = Project::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Project::select('count(*) as allcount')
            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->whereIN('projectid',[15,13,14,97])

            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get()->load('markets','ma_template');

        $data_arr = array();
        foreach ($records as $record) {
//            dd($record->toArray());
//            dd($record->ma_template);



            $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            if($record->buildinfo_console == 0){
                $btn = $btn. '   <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            }
            $btn = $btn.'<br><br> <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'"  class="btn btn-info fakeProject"><i class="ti-info-alt"></i></a>';


            if(isset($record->logo)){
//                        $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../uploads/project/$record->projectname/thumbnail/$record->logo'>";
                $logo = "<img class='rounded mx-auto d-block'  width='100px'  height='100px'  src='../storage/projects/$record->ma_da/$record->projectname/lg114.png'>";

            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }

            $project    = '<span class="font-16 mt-0" style="line-height:0.5;font-weight: 500;"> '.$record->projectname.' </span>';
            $template = '<span class="text-muted" style="line-height:0.5"> ('.$record->ma_template->template.') </span>';
            $version  = 'Version: <span class="text-muted" style="line-height:0.5"> '.$record->buildinfo_vernum .' | '.$record->buildinfo_verstr.' </span>';

            $package = $status_app =  '';
            $keystore = 'Key: ';
            $sdk = 'SDK : <span class="badge badge-secondary" style="font-size: 12px">'.$record->buildinfo_keystore.'</span>';
//            dd($record->markets);
            $badges = [
//                'secondary',
                'primary',
                'success',
                'info',
                'warning',
                'danger',
                'dark',
            ];

            foreach ($record->markets as $key=>$market){
                if($market->pivot->package){
                    $package .= '<span style="color:black;line-height:0.5"><img src="img/icon/'.$market->market_logo.'"> '.$market->pivot->package.'</span> <button onclick="copyPackage(this)" type="button" data-text="'.$market->pivot->package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';

                    $status = $market->pivot->status_app;
                    switch ($status){
                        case 0:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-secondary">Mặc định</span>';
                            break;
                        case 1:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-success">Publish</span>';
                            break;
                        case 2:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-warning">Suppend</span>';
                            break;
                        case 3:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-info">UnPublish</span>';
                            break;
                        case 4:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-primary">Remove</span>';
                            break;
                        case 5:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-dark">Reject</span>';
                            break;
                        case 6:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-danger">Check</span>';
                            break;
                        case 7:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-warning">Pending</span>';
                            break;
                        default:
                            $status_app .=  '<img src="img/icon/'.$market->market_logo.'"><span class="badge badge-secondary">Mặc định</span>';
                            break;
                    }
                }
                if($market->pivot->sdk){
                    $sdk .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px">'.strtoupper($market->market_name[0]).': '.$market->pivot->sdk.'</span> ';
                }
                if($market->pivot->keystore){
                    $keystore .= '<span class="badge badge-'.$badges[$key].'" style="font-size: 12px">'.strtoupper($market->market_name[0]).': '.$market->pivot->keystore.'</span>';
                }
            }

            $data_arr[] = array(
                "logo" => $logo,
                "projectname"=>$project.$template.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore,
                "markets"=>$package,
                "status"=>$status_app,


//                "projectname"=>$data_projectname.$data_template.' - '. $data_title_app. $project_file.$buildinfo_app_name_x.$abc.$sdk_profile.$keystore_profile.$des_en. $des_vn,
//                "Chplay_package" =>$package_chplay.$package_amazon.$package_samsung.$package_xiaomi.$package_oppo.$package_vivo.$package_Huawei,
//                "status" => $status,
//                'Chplay_buildinfo_store_name_x' => $dev_name_chplay,
//                'Amazon_buildinfo_store_name_x' => $dev_name_amazon,
//                'Samsung_buildinfo_store_name_x' => $dev_name_samsung,
//                'Xiaomi_buildinfo_store_name_x' => $dev_name_xiaomi,
//                'Oppo_buildinfo_store_name_x' => $dev_name_oppo,
//                'Vivo_buildinfo_store_name_x' => $dev_name_vivo,
//                'Huawei_buildinfo_store_name_x' => $dev_name_huawei,
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
