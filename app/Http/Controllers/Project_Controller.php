<?php

namespace App\Http\Controllers;

use App\Models\Keystore;
use App\Models\MarketProject;
use App\Models\Markets;
use App\Models\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use ZipArchive;
use File;
use function React\Promise\all;

class Project_Controller extends Controller
{
    public function index(Request $request)
    {
        $header = [
            'title' => 'Project',

            'button' => [
                'Create'            => ['id'=>'createNewProject','style'=>'primary'],
                'Build And Check'   => ['id'=>'build_check','style'=>'warning'],
                'Status'            => ['id'=>'dev_status','style'=>'info'],
                'KeyStore'          => ['id'=>'change_keystore','style'=>'success'],
                'SDK'               => ['id'=>'change_sdk','style'=>'danger'],
                'Upload Status'     => ['id'=>'change_upload_status','style'=>'secondary'],
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
            ->with('markets.pivot.dev.ga','ma_template','da')
            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
//            $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->projectid.'" class="btn btn-warning editProject"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            if($record->buildinfo_console == 0){
                $btn = $btn. ' <br><br>  <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            }
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'"  class="btn btn-info fakeProject"><i class="ti-info-alt"></i></a>';

            $mada =  $template = '';
            if($record->da){
                $mada = $record->da->ma_da;
            }
            if($record->ma_template){
                $template = $record->ma_template->template;
            }
            if(isset($record->logo)){
                $logo = '<img class="rounded mx-auto d-block"  width="100px"  height="100px"  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg114.png').'">';
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }

            $project    = '<span class="h3 font-16 "> '.$record->projectname.' </span>';
            $template   = '<span class="text-muted" style="line-height:0.5"> ('.$template.') </span>';
            $mada       = '<span class="" style="line-height:0.5"> - '.$mada.'</span>';

            $version  = 'Version: <span class="text-muted" style="line-height:0.5"> '.$record->buildinfo_vernum .' | '.$record->buildinfo_verstr.' </span>';

            $package = $status_app =$dev = $ga = $result =  '';
            $keystore = 'Key: ';
            $sdk = 'SDK : <span class="badge badge-secondary" style="font-size: 12px">'.$record->buildinfo_keystore.'</span>';
            $badges = [
                'primary',
                'success',
                'info',
                'warning',
                'danger',
                'dark',
                'secondary',
            ];
            foreach ($record->markets as $key=>$market){
                if($market->pivot->package){
                    $result .= '<div class="font-16">';
                    $package .= '<p class="card-title-desc font-16"><img src="img/icon/'.$market->market_logo.'"> '.$market->pivot->package.'</p>';
                    if($market->pivot->sdk){
                        $sdk .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->sdk.' </span> ';
                    }
                    if($market->pivot->keystore){
                        $keystore .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->keystore.' </span> ';
                    }

                    $result .= '<img src="img/icon/'.$market->market_logo.'"> ';

                    $result .= $market->pivot->aab_link ? ' <a href="'.$market->pivot->aab_link.'"  target="_blank" class="badge badge-success">AAB</a> ' : ' <a class="badge badge-secondary">AAB</a> ' ;

                    $result .= $market->pivot->apk_link ? ' <a href="'.$market->pivot->apk_link.'"  target="_blank" class="badge badge-success">APK</a> ' : ' <a class="badge badge-secondary">APK</a> ' ;
                    $status = $market->pivot->status_app;
                    $result .=  '<p data-package="'.$market->pivot->id.'" class="check_Status_'.$market->market_name.' badge badge-';
                    switch ($status){
                        case 1:
//                            $result .=  ' <p class="badge badge-success "> Publish</p> ';
                            $result .=  'success "> Publish';
                            break;
                        case 2:
//                            $result .=  ' <p class="badge badge-warning "> Suppend</p> ';
                            $result .=  'warning "> Suppend';
                            break;
                        case 3:
//                            $result .=  ' <p class="badge badge-info"> UnPublish</p> ';
                            $result .=  'info"> UnPublish';
                            break;
                        case 4:
//                            $result .=  ' <p class="badge badge-primary"> Remove</p> ';
                            $result .=  'primary"> Remove';
                            break;
                        case 5:
//                            $result .=  ' <p class="badge badge-dark"> Reject</p> ';
                            $result .=  'dark"> Reject';
                            break;
                        case 6:
//                            $result .=  ' <p class="badge badge-danger "> Check</p> ';
                            $result .=  'danger"> Check ';
                            break;
                        case 7:
//                            $result .=  ' <p class="badge badge-warning"> Pending</p> ';
                            $result .=  'warning"> Pending';
                            break;
                        default:
//                            $result .=  ' <p class="badge badge-secondary"> Mặc định</p> ';
                            $result .=  'secondary"> Mặc định';
                            break;
                    }
                    $result .=  '</p>';
                    if($market->pivot->dev_id){
                        $result .= ' <span class="badge badge-'.$badges[$key].'">'.$market->pivot->dev->dev_name.'</span> ';
                        if ($market->pivot->dev->ga_id){
                            $result .= ' <span class="badge badge-'.$badges[$key].'">'.$market->pivot->dev->ga->ga_name.'</span> ';
                        }
                    }
                    $result .= '</div>';
                }


            }




            $data_arr[] = array(
                "projectid" => $record->projectid,
                "logo" => $logo,
                "projectname"=>$project.$template.$mada.'<br>'.$record->title_app.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore,
                "markets"=>$package,
//                "status"=>'<div>'.@$download_apk.@$download_aab.$status_app.@$dev.@$ga .'</div>',
                "status"=>@$result,
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

    public function create(Request $request){
        $rules = [
            'projectname' =>'required|unique:ngocphandang_project,projectname',
            'ma_da' => 'required|not_in:0',
            'template' => 'required|not_in:0',

            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
            'project_file' => 'mimes:zip',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
            'ma_da.required'=>'Mã dự án không để trống',

            'ma_da.not_in'=>'Mã dự án không để trống',
            'template.required'=>'Mã template không để trống',
            'title_app.required'=>'Tiêu đề ứng không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'project_file.mimes'=>'*.zip',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = new Project();
        $data['projectname'] = $request->projectname;
        $data['template'] = $request->template;
        $data['ma_da'] = $request->ma_da;
        $data['title_app'] = $request->title_app;

        $data['buildinfo_link_fanpage'] = $request->buildinfo_link_fanpage;
        $data['buildinfo_link_website'] =  $request->buildinfo_link_website;

        $data['buildinfo_api_key_x'] = $request->buildinfo_api_key_x;
        $data['buildinfo_console'] = 0;
        $data['buildinfo_vernum' ]= $request->buildinfo_vernum;
        $data['buildinfo_verstr'] = $request->buildinfo_verstr;
        $data['data_onoff'] = $request->data_status ? (int)  $request->data_status :0;

        if($request->project_file){
            $path_file = storage_path('app/public/projects/'.$request->project_da_name.'/'.$request->projectname.'/');
            if (!file_exists($path_file)) {
                mkdir($path_file, 0777, true);
            }
            $file = $request->project_file;
            $extension = $file->getClientOriginalExtension();
            $file_name = 'DATA'.'.'.$extension;
            $data['project_file'] = $file_name;
            $file->move($path_file, $file_name);
        }
        if(isset($request->logo)){
            $path_logo = storage_path('app/public/projects/'.$request->project_da_name.'/'.$request->projectname.'/');
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


        $inset_market = [];

        foreach ($request->market as $key=>$value){
            if($value['package']){
                $inset_market[$key] = [
                    'package' => $value['package'],
                    'dev_id' => @$value['dev_id'],
                    'keystore' => @$value['keystore'],
                    'sdk' => @$value['sdk'],
                    'app_link' => @$value['app_link'],
                    'policy_link' => @$value['policy_link'],
                    'ads' => @json_encode($value['ads']),
                    'app_name_x' => @$value['app_name_x'],
                    'appID' => @$value['appID'],
                    'video_link' => @$value['video_link'],
                ];
            }
        }

        $data->save();
        $data->markets()->attach($inset_market);
        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id){

        $project = Project::find($id);

        return response()->json($project->load('markets.pivot.dev','markets.pivot.keystores','da','ma_template.markets','lang'));
//        return response()->json($project);
    }

    public function update(Request $request){
        $id = $request->project_id;
        $rules = [
            'projectname' =>'unique:ngocphandang_project,projectname,'.$id.',projectid',
//            'ma_da' => 'required',
            'template' => 'required',
//            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
            'project_file' => 'mimes:zip',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
            'template.required'=>'Mã template không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'project_file.mimes'=>'*.zip',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = Project::find($id)->load('markets');

        $data->template = $request->template  ;
//        $data->ma_da = $request->ma_da;
        $data->title_app = $request->title_app;

        $data['buildinfo_link_fanpage'] = $request->buildinfo_link_fanpage;
        $data['buildinfo_link_website'] =  $request->buildinfo_link_website;

        $data['buildinfo_api_key_x'] = $request->buildinfo_api_key_x;

        $data['buildinfo_vernum' ]= $request->buildinfo_vernum;
        $data['buildinfo_verstr'] = $request->buildinfo_verstr;
        $data['data_onoff'] = $request->data_status ? (int)  $request->data_status :0;

        if($request->project_file){
            $du_an = preg_split("/[-]+/",$request->projectname) ? preg_split("/[-]+/",$request->projectname)[0] : 'DA';
            $path_file = storage_path('app/public/projects/'.$request->project_da_name.'/'.$request->projectname.'/');
            if (!file_exists($path_file)) {
                mkdir($path_file, 0777, true);
            }
            $file = $request->project_file;
            $extension = $file->getClientOriginalExtension();
            $file_name = 'DATA'.'.'.$extension;
            $data['project_file'] = $file_name;
            $file->move($path_file, $file_name);
        }
        if(isset($request->logo)){
            $path_logo = storage_path('app/public/projects/'.$request->project_da_name.'/'.$request->projectname.'/');
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


        if($request->projectname != $data->projectname){
            try {
                $dir = (storage_path('app/public/projects/'.$request->project_da_name.'/'));
                rename($dir.$data->projectname, $dir.$request->projectname);
                $this->deleteDirectory($dir . $data->projectname);
            }catch (\Exception $exception) {
                Log::error('Message: rename folder Project' . $exception->getMessage() . '--' . $exception->getLine());
            }

        }



        $data->projectname = $request->projectname;
        $data->save();
        try {
            $inset_market = [];
            foreach ($request->market as $key=>$value){
                if($value['package']){
                    $inset_market[$key] = [
                        'package' => $value['package'],
                        'dev_id' => @$value['dev_id']   ,
                        'keystore' => @$value['keystore'],
                        'sdk' => @$value['sdk'],
                        'app_link' => @$value['app_link'],
                        'policy_link' => @$value['policy_link'],
                        'ads' => json_encode(@$value['ads']),
                        'app_name_x' => @$value['app_name_x'],
                        'appID' => @$value['appID'],
                        'video_link' => @$value['video_link'],
                    ];
                }
            }
            $data->markets()->sync($inset_market);
        }catch (\Exception $exception) {
            Log::error('Message: Inset_market' . $exception->getMessage() . '--' . $exception->getLine());
        }
        return response()->json(['success'=>'Thành công']);

    }

    public function delete($id){
        $project = Project::find($id);
//        $path =   public_path('uploads/project/').$project->projectname;
//        $this->deleteDirectory($path_image);
        $project->markets()->sync([]);
        $project->lang()->sync([]);
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

    public function check_build(Request $request){
        $project = Project::select('projectid','projectname','buildinfo_verstr','buildinfo_vernum')->whereIN('projectname',$request->projectname)->get();
        return response()->json($project);
    }

    public function updateConsole(Request $request){
        $console = $request->buildinfo_console;


        switch ($console){
            case '0':
                $project = Project::findorFail($request->projectID)->update([
                    'buildinfo_console' => 0,
                    'buildinfo_mess' => '',
                    'time_mess' =>time(),
                    'buildinfo_time' => time(),
                ]);
                break;
            default:
                $data = $request->data;
                foreach (array_filter($data) as $item){
                    $arr = explode("|",$item);
                    Project::updateOrCreate(
                        [
                            "projectname" => trim($arr[0]),
                        ],
                        [
                            "buildinfo_vernum" => (int)trim($arr[1]),
                            'buildinfo_verstr' => trim($arr[2]),
                            'buildinfo_console' => (int)$request->buildinfo_console,
                            'buildinfo_mess' => 'Chờ xử lý',
                            'time_mess' => time(),
                            'buildinfo_time' =>time(),

                        ]);
                }
        }


        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function updateMultiple(Request $request){

        $data = explode("\r\n",$request->changeMultiple);
        $markets = Markets::all()->pluck('id')->toArray();
        $action = $request->action;

        if ($action == 'upload_status'){
            $projects = Project::whereIN('projectname',array_map('trim', $data))->pluck('projectid');
            $projects_market =
                MarketProject::whereIN('project_id',$projects)
                    ->whereIn('market_id',$request->market_upload)
                    ->get();
            foreach ($projects_market as $project){
                $status = $project->status_upload;
                switch ($status){
                    case 0:
                        $status = 1;
                        break;
                    case 2:
                        $status = 2;
                        break;
                }
                $project->status_upload = $status;
                $project->save();
            }

        }else{
            foreach ($data as $item){
                try {
                    [$ID_Project, $Key_C, $Key_A, $Key_S, $Key_X, $Key_O, $Key_V, $Key_H ] = explode("|",$item);
                    $keystore = [
                        $Key_C, $Key_A, $Key_S, $Key_X, $Key_O, $Key_V, $Key_H
                    ];
                    $markets_keystore = array_combine($markets, $keystore);
                    $project = Project::where('projectname',trim($ID_Project))->firstorfail();
                    switch ($action){
                        case 'keystore':
                            foreach ($markets_keystore as $key=>$market_keystore){
                                MarketProject::where('project_id',$project->projectid)
                                    ->where('market_id',$key)
                                    ->update(['keystore'=>trim($market_keystore)]);
                                Keystore::updateorcreate([
                                    'name_keystore' => trim($market_keystore)
                                ]);
                            }
                            break;
                        case 'sdk':
                            foreach ($markets_keystore as $key=>$market_keystore){
                                MarketProject::where('project_id',$project->projectid)
                                    ->where('market_id',$key)
                                    ->update(['sdk'=>trim($market_keystore)]);
                            }
                            break;
                    }

                }catch (\Exception $exception) {
                    \Illuminate\Support\Facades\Log::error('Message:' . $exception->getMessage() . '--- chang key : ' . $exception->getLine());
                }
            }
        }

        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function updateDevStatus(Request $request){
        $data = explode("\r\n",$request->project_data);
        $markets = $request->market;
        $projects = Project::whereIN('projectname',$data)->get();
        foreach ($projects as $project){
            foreach ($markets as $key=>$market){
                $market_project =  MarketProject::where('project_id',$project->projectid)
                    ->where('market_id',$key)->first();
                if($market_project){
                    $market_project->dev_id = $market['dev_id'] == 0 ? $market_project->dev_id : $market['dev_id'];
                    $market_project->status_app = $market['status_app'] == 0 ? $market_project->status_app : $market['status_app'];
                    $market_project->save();
                }
            }
        }
        return response()->json(['success'=>'Cập nhật thành công ']);
    }

    public function process()
    {
        $header = [
            'title' => 'Process',
            'button' => [
                'All'       => ['id'=>'all','style'=>'primary'],
                'Chờ xử lý' => ['id'=>'WaitProcessing','style'=>'warning'],
                'Đang xử lý'=> ['id'=>'Processing','style'=>'info'],
                'Kết thúc'  => ['id'=>'End','style'=>'success'],
                'Remove'    => ['id'=>'RemoveA','style'=>'danger'],
            ]
        ];
        return view('project.process')->with(compact('header'));
    }

    public function getProcess(Request $request){

        $status_console = '1%4%2%5%3%6%7%8';
        if(isset($request->console_status)){
            $status_console = $request->console_status;
        }

        $status_console = explode('%',$status_console);



        if(isset($request->remove_status)){
            $remove_status = $request->remove_status;
            $remove_status = explode('%',$remove_status);
            $projects = Project::whereIn('buildinfo_console',$remove_status)->get();
            foreach ($projects as $project){
                Project::updateOrCreate(
                    [
                        "projectid" => $project->projectid,
                    ],
                    [
                        'buildinfo_console' => 0,
                        'buildinfo_mess' => '',
                        'time_mess' =>time(),
                        'buildinfo_time' => time(),

                    ]);
            }
        }

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
            ->where('buildinfo_console','<>',0)

            ->count();
        $totalRecordswithFilter = Project::select('count(*) as allcount')
            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->where('buildinfo_console','<>',0)
            ->whereIn('buildinfo_console',$status_console)

            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->with('markets','ma_template','da')
            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->whereIn('buildinfo_console',$status_console)

            ->where('buildinfo_console','<>',0)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)"data-id="'.$record->projectid.'" class="btn btn-warning removeProject"><i class="mdi mdi-file-move"></i></a>';

            $mada =  $template = '';
            if($record->da){
                $mada = $record->da->ma_da;
            }
            if($record->ma_template){
                $template = $record->ma_template->template;
            }

            if(isset($record->logo)){
                $logo = '<img class="rounded mx-auto d-block"  width="100px"  height="100px"  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg114.png').'">';
            }else{
                $logo = '<img class="rounded mx-auto d-block" width="100px" height="100px" src="assets\images\logo-sm.png">';
            }

            $project    = '<span class="h3 font-16 "> '.$record->projectname.' </span>';
            $template = '<span class="text-muted" style="line-height:0.5"> ('.$template.') </span>';
            $mada = '<span class="" style="line-height:0.5"> - '.$mada.'</span>';

            $version  = 'Version: <span class="text-muted" style="line-height:0.5"> '.$record->buildinfo_vernum .' | '.$record->buildinfo_verstr.' </span>';

            $package = $status_app =$dev =  '';
            $keystore = 'Key: ';
            $sdk = 'SDK : <span class="badge badge-secondary" style="font-size: 12px">'.$record->buildinfo_keystore.'</span>';
            $badges = [
                'primary',
                'success',
                'info',
                'warning',
                'danger',
                'dark',
                'secondary',
            ];

            foreach ($record->markets as $key=>$market){

                if($market->pivot->package){
                    $package .= '<p class="card-title-desc font-16"><img src="img/icon/'.$market->market_logo.'"> '.$market->pivot->package.'</p>';



                    if($market->pivot->sdk){
                        $sdk .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->sdk.' </span> ';
                    }
                    if($market->pivot->keystore){
                        $keystore .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->keystore.' </span> ';
                    }
                }
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
                "projectid" => $record->projectid,
                "logo" => $logo,
                "projectname"=>$project.$template.$mada.'<br>'.$record->title_app.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore,
                "markets"=>$package,

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

    public function show($id){
        $project = Project::find($id)->load('lang','da','ma_template');
        return view('project.show')->with(compact('project'));
    }


    public function upload()
    {
        $header = [
            'title' => 'Upload Project',
            'button' => [
//                'All'       => ['id'=>'all','style'=>'primary'],
//                'Chờ xử lý' => ['id'=>'WaitProcessing','style'=>'warning'],
//                'Đang xử lý'=> ['id'=>'Processing','style'=>'info'],
//                'Kết thúc'  => ['id'=>'End','style'=>'success'],
//                'Remove'    => ['id'=>'RemoveA','style'=>'danger'],
            ]
        ];




//
//
//        $projects = Project::has('lang')->where('status_design',4)->orderByDesc('projectname')->get();
////        return view('design_content.index')->with(compact('projects'));
////        dd($projects);

        return view('project.upload')->with(compact('header'));
    }

    public function getProjectUpload(Request $request){

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
            ->whereHas('markets',function($q)
            {
                $q
                    ->where('status_upload', 1);
            })
            ->count();
        $totalRecordswithFilter = Project::select('count(*) as allcount')
            ->whereHas('markets',function($q)
            {
                $q
                    ->where('status_upload', 1);
            })
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->whereHas('markets',function($q)
            {
                $q
                    ->where('status_upload', 1);
            })
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




    public function download($id)
    {
        Artisan::call('optimize:clear');
        $project = Project::find($id);

        $zip_file = $project->projectname.'.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

//        $path = storage_path('invoices');
        $path = storage_path('app/public/projects/'.$project->da->ma_da.'/'.$project->projectname);
        try {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            foreach ($files as $name => $file)
            {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();

                    // extracting filename with substr/strlen
                    $relativePath = $project->projectname.'/' . substr($filePath, strlen($path) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            return \Response::download($zip_file, $project->projectname.'.zip', array('Content-Type: application/octet-stream','Content-Length: '. filesize($zip_file)))->deleteFileAfterSend(true);
//            return response()->download($zip_file);
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--' . $exception->getLine());
        }
    }


    public function update_upload_status($id){
        $project = MarketProject::find($id);
        $project->status_upload = 3;
        $project->save();
        return response()->json(['success'=>'Thành công']);
    }


    public function fake($id)
    {
        $project = Project::find($id);
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

}
