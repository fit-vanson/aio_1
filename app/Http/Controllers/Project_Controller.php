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
    public function index()
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
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->orwhere('projectid', 'like', '%' . $searchValue . '%')
            ->orwhere('title_app', 'like', '%' . $searchValue . '%')
            ->orwhereHas('markets', function ($query) use ($searchValue) {
                $query
                    ->where('keystore', 'like', '%' . $searchValue . '%')
                    ->orwhere('package', 'like', '%' . $searchValue . '%')
                    ->orwhere('appID', 'like', '%' . $searchValue . '%');
            })
            ->orwhereHas('dev', function ($query) use ($searchValue) {
                $query
                    ->where('dev_name', 'like', '%' . $searchValue . '%');
            })
            ->orwhereHas('ma_template', function ($query) use ($searchValue) {
                $query
                    ->where('template', 'like', '%' . $searchValue . '%');
            })
            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->with('markets.pivot.dev.ga','ma_template','da')
            ->where('projectname', 'like', '%' . $searchValue . '%')
            ->orwhere('projectid', 'like', '%' . $searchValue . '%')
            ->orwhere('title_app', 'like', '%' . $searchValue . '%')
            ->orwhereHas('markets', function ($query) use ($searchValue) {
                $query
                    ->where('keystore', 'like', '%' . $searchValue . '%')
                    ->orwhere('package', 'like', '%' . $searchValue . '%')
                    ->orwhere('appID', 'like', '%' . $searchValue . '%');
            })
            ->orwhereHas('dev', function ($query) use ($searchValue) {
                $query
                    ->where('dev_name', 'like', '%' . $searchValue . '%');
            })
            ->orwhereHas('ma_template', function ($query) use ($searchValue) {
                $query
                    ->where('template', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();

        $url_rand = uniqid();
        foreach ($records as $record) {
//            $url_rand = uniqid();
            $btn = '<div class="button-items">';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->projectid.'" class="btn btn-warning editProject"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            if($record->buildinfo_console == 0){
                $btn = $btn. ' <br><br>  <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            }
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->projectid.'"  class="btn btn-dark fakeProject"><i class="ti-info-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->projectid.'"  class="btn btn-info copyProject"><i class="mdi mdi-content-copy"></i></a>';

            $btn = $btn.'</div>';
            $mada =  $template = '';
            if($record->da){
                $mada = $record->da->ma_da;
            }
            if($record->ma_template){
                $template = $record->ma_template->template;
            }
            if(isset($record->logo)){
//                $logo = '<img class="rounded mx-auto d-block"  width="100px"  height="100px"  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg114.png').'">';
//                $logo = '<a class=" image-popup-no-margins image" style="margin:5px" href="'.url('api/picture/'.$url_rand.'/'.$mada.'&'.$record->projectname.'&lg.png').'" title="Logo">' .
//                    '<img class="rounded mx-auto d-block"  src="'.url('api/picture/'.$url_rand.'/'.$mada.'&'.$record->projectname.'&lg114.png').'" alt="logo" height="100">' .
//                    '</a>';
                $logo = '<a class=" image-popup-no-margins image" style="margin:5px" href="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg.png?time='.$url_rand).'" title="Logo">' .
                    '<img class="rounded mx-auto d-block"  src="'.url('storage/projects/'.$mada.'/'.$record->projectname.'/lg114.png?time='.$url_rand).'" alt="logo" height="100">' .
                    '</a>';
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

            $data_check = $record->data_check;
            switch ($data_check){
                case '0':
                    $status_check = 'DATA : <span class="badge badge-danger" style="font-size: 12px">No Valid</span> ';
                    break;
                case '1':
                    $status_check = 'DATA : <span class="badge badge-success" style="font-size: 12px">Valid</span>';
                    break;
            }
            $data_design = $record->status_design;
            switch ($data_design){
                case 0:
                    $status_design =' DESIGN: <span style="font-size: 100%" class="badge badge-secondary">G???i ch??? duy???t</span>' ;
                    break;
                case 1:
                    $status_design = ' DESIGN: <span style="font-size: 100%" class="badge badge-info">???? ch???nh s???a, c???n duy???t l???i</span>';
                    break;
                case 2:
                    $status_design = ' DESIGN: <span style="font-size: 100%" class="badge badge-warning">Fail, c???n ch???nh s???a</span>';
                    break;
                case 3:
                    $status_design = ' DESIGN: <span style="font-size: 100%" class="badge badge-danger">Fail, Project lo???i kh???i d??? ??n</span>';
                    break;
                case 4:
                    $status_design = ' DESIGN: <span style="font-size: 100%" class="badge badge-success">Done, K???t th??c Project</span>';
                    break;
            }
            foreach ($record->markets as $key=>$market){
                if($market->pivot->package){
                    $ads = json_decode($market->pivot->ads,true);
                    if(isset($ads) && count(array_filter($ads)) != 0){
                        $ads_status = ' <span class="badge badge-success" style="font-size: 11px">ads</span>';
                    }else{
                        $ads_status = ' ';
                    }

                    $result .= '<div class="font-16">';
                    $package .= '<p class="card-title-desc font-16"><img src="img/icon/'.$market->market_logo.'"><a id="app_link_'.$market->pivot->id.'" href="'.$market->pivot->app_link.'"  target="_blank"> '.$market->pivot->package.'</a>'.$ads_status.'</p>';
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
                    $result .=  '<span data-package="'.$market->pivot->id.'" class="check_Status_'.$market->market_name.' badge badge-';
                    switch ($status){
                        case 1:
                            $result .=  'success "> Publish';
                            break;
                        case 2:
                            $result .=  'warning "> UnPublish';
                            break;
                        case 3:
                            $result .=  'info"> Remove';
                            break;
                        case 4:
                            $result .=  'primary"> Reject';
                            break;
                        case 5:
                            $result .=  'dark"> Suspend';
                            break;
                        case 6:
                            $result .=  'danger"> Check ';
                            break;
                        default:
                            $result .=  'secondary"> M???c ?????nh';
                            break;
                    }
                    $result .=  '</span>';
                    if(isset($market->pivot->dev_id)){
                        $result .= ' <span class="badge badge-'.$badges[$key].'">'.@$market->pivot->dev->dev_name.'</span> ';
                        if (isset($market->pivot->dev->ga_id)){
                            $result .= ' <span class="badge badge-'.$badges[$key].'">'.@$market->pivot->dev->ga->ga_name.'</span> ';
                        }
                    }
                    $result .= '</div>';
                }
            }

            $data_arr[] = array(
                "projectid" => $record->projectid,
                "logo" => $logo,
                "projectname"=>$project.$template.$mada.'<br>'.$record->title_app.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore.'<br>'.$status_check.' - '.$status_design,
                "markets"=>$package,
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
            'projectname.unique'=>'T??n Project ???? t???n t???i',
            'projectname.required'=>'T??n Project kh??ng ????? tr???ng',
            'ma_da.required'=>'M?? d??? ??n kh??ng ????? tr???ng',

            'ma_da.not_in'=>'M?? d??? ??n kh??ng ????? tr???ng',
            'template.required'=>'M?? template kh??ng ????? tr???ng',
            'title_app.required'=>'Ti??u ????? ???ng kh??ng ????? tr???ng',
            'buildinfo_vernum.required'=>'Version Number kh??ng ????? tr???ng',
            'buildinfo_verstr.required'=>'Version String kh??ng ????? tr???ng',
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
                    'package' => @$value['package'],
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
        return response()->json(['success'=>'Th??nh c??ng']);
    }

    public function edit($id){
        $project = Project::find($id);
        return response()->json($project->load('markets.pivot.dev','markets.pivot.keystores','da','ma_template.markets','lang'));
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
            'projectname.unique'=>'T??n Project ???? t???n t???i',
            'projectname.required'=>'T??n Project kh??ng ????? tr???ng',
            'template.required'=>'M?? template kh??ng ????? tr???ng',
            'buildinfo_vernum.required'=>'Version Number kh??ng ????? tr???ng',
            'buildinfo_verstr.required'=>'Version String kh??ng ????? tr???ng',
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
            foreach ($request->market as $key => $value){
                if($value['package']){
                    $inset_market[$key] = [
                        'package' => $value['package'],
                        'ads' => json_encode($value['ads']),
                        ];
                    if(isset($value['dev_id'])){
                        $inset_market[$key] += [
                            'dev_id' => $value['dev_id']
                        ];
                    }
                    if(isset($value['keystore'])){
                        $inset_market[$key] += [
                            'keystore' => $value['keystore']
                        ];
                    }
                    if(isset($value['sdk'])){
                        $inset_market[$key] += [
                            'sdk' => $value['sdk']
                        ];
                    }
                    if(isset($value['app_link'])){
                        $inset_market[$key] += [
                            'app_link' => $value['app_link']
                        ];
                    }
                    if(isset($value['policy_link'])){
                        $inset_market[$key] += [
                            'policy_link' => $value['policy_link']
                        ];
                    }
                    if(isset($value['app_name_x'])){
                        $inset_market[$key] += [
                            'app_name_x' => $value['app_name_x']
                        ];
                    }
                    if(isset($value['appID'])){
                        $inset_market[$key] += [
                            'appID' => $value['appID']
                        ];
                    }
                    if(isset($value['video_link'])){
                        $inset_market[$key] += [
                            'video_link' => $value['video_link']
                        ];
                    }

                }
            }
            $data->markets()->sync($inset_market,false);
        }catch (\Exception $exception) {
            Log::error('Message: Inset_market' . $exception->getMessage() . '--' . $exception->getLine());
        }
        return response()->json(['success'=>'Th??nh c??ng']);

    }

    public function delete($id){
        $project = Project::find($id);
        try {
            $path =   storage_path('app/public/projects/').$project->da->ma_da.'/'.$project->projectname;
            $this->deleteDirectory($path);

        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Delete Project : ' . $exception->getLine());
        }
        $project->markets()->sync([]);
        $project->lang()->sync([]);
        $project->delete();
        return response()->json(['success'=>'X??a th??nh c??ng.']);

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
                            'buildinfo_mess' => 'Ch??? x??? l??',
                            'time_mess' => time(),
                            'buildinfo_time' =>time(),

                        ]);
                }
        }


        return response()->json(['success'=>'C???p nh???t th??nh c??ng']);
    }

    public function updateMultiple(Request $request){
        $action = $request->action;
        $data = explode("\r\n",$request->changeMultiple);
        $markets = $request->market_upload;
        $array = array();
        try {
            foreach (array_filter($data) as $item)
            {
                $path  = preg_split("/[|]+/",$item,2);
                if(isset($path[1])){
                    $array1 = @explode('|', $path[1]);

                }else{
                    $array1 = $markets;
                }
                if(count($array1) == count($markets)){
                    $array[trim($path[0])] =  array_combine( $markets, $array1 );
                }else{
                    return response()->json(['errors'=>trim($path[0]).' sai ?????nh d???ng.']);
                }
            }


            $MarketProjectInstance = new MarketProject();
            $valueInsert = $valueKey = [];

            switch ($action){
                case 'keystore':
                    foreach ($array as $key=>$value){
                        $project = Project::where('projectname',$key)->pluck('projectid');
                        $projects_market = MarketProject::whereIN('project_id',$project)->get();
                        foreach ($projects_market as $project_market){
                            $valueInsert[] = [
                                'id' =>$project_market->id,
                                'keystore' =>@trim($value[$project_market->market_id])
                            ];
                            Keystore::updateorcreate([
                                'name_keystore' => @trim($value[$project_market->market_id])
                            ]);
                        }

                    }
                    break;
                case 'upload_status':
                    foreach ($array as $key=>$value){
                        $project = Project::where('projectname',$key)->pluck('projectid');
                        $projects_market =
                            MarketProject::whereIN('project_id',$project)->whereIN('market_id',$value)->get();
                        foreach ($projects_market as $project_market){
//                            $status = $project_market->status_upload;
                            $status = $request->status_upload ?? 0;
//                            switch ($status){
//                                case 0|3:
//                                    $status = 1;
//                                    break;
//                                case 2:
//                                    $status = 2;
//                                    break;
//
//                            }
                            $valueInsert[] = [
                                'id' =>$project_market->id,
                                'status_upload' => $status
                            ];
                        }
                    }
                    break;
                case 'sdk':
                    foreach ($array as $key=>$value){
                        $project = Project::where('projectname',$key)->pluck('projectid');
                        $projects_market = MarketProject::whereIN('project_id',$project)->get();
                        foreach ($projects_market as $project_market){
                            $valueInsert[] = [
                                'id' =>$project_market->id,
                                'sdk' =>@trim($value[$project_market->market_id])
                            ];
                        }
                    }
                    break;
            }
            $index = 'id';
            batch()->update($MarketProjectInstance, $valueInsert, $index);
        }catch (\Exception $exception) {
            Log::error('Message:updateMultiple---' . $exception->getMessage() . '--' . $exception->getLine());
        }
        return response()->json(['success'=>'C???p nh???t th??nh c??ng ']);
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
        return response()->json(['success'=>'C???p nh???t th??nh c??ng ']);
    }

    public function process()
    {
        $header = [
            'title' => 'Process',
            'button' => [
                'All'       => ['id'=>'all','style'=>'primary'],
                'Ch??? x??? l??' => ['id'=>'WaitProcessing','style'=>'warning'],
                '??ang x??? l??'=> ['id'=>'Processing','style'=>'info'],
                'K???t th??c'  => ['id'=>'End','style'=>'success'],
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
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orwhereHas('markets', function ($query) use ($searchValue) {
                        $query
                            ->where('keystore', 'like', '%' . $searchValue . '%')
                            ->orwhere('package', 'like', '%' . $searchValue . '%');
                    })
                    ->orwhereHas('dev', function ($query) use ($searchValue) {
                        $query
                            ->where('dev_name', 'like', '%' . $searchValue . '%');
                    });
            })
            ->where('buildinfo_console','<>',0)
            ->whereIn('buildinfo_console',$status_console)
            ->count();
        $records = Project::orderBy($columnName, $columnSortOrder)
            ->with('markets','ma_template','da')
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orwhereHas('markets', function ($query) use ($searchValue) {
                        $query
                            ->where('keystore', 'like', '%' . $searchValue . '%')
                            ->orwhere('package', 'like', '%' . $searchValue . '%');
                    })
                    ->orwhereHas('dev', function ($query) use ($searchValue) {
                        $query
                            ->where('dev_name', 'like', '%' . $searchValue . '%');
                    });
            })
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

        $project = Project::findorfail($id)->load('markets.pivot.dev','markets.pivot.keystores','da','ma_template.markets','lang');
//        return response()->json($project->load('markets.pivot.dev','markets.pivot.keystores','da','ma_template.markets','lang'));
        return view('project.show')->with(compact('project'));
    }

    public function upload()
    {
        $header = [
            'title' => 'Upload Project',
            'button' => []
        ];

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
        return response()->json(['success'=>'Th??nh c??ng']);
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

    public function manage ()
    {
        $header = [
            'title' => 'Qu???n l?? app '.$_GET['market'],
            'button' => []
        ];
        return view('project.manage')->with(compact('header'));
    }

    public function getManage(Request $request){

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

        $totalRecords = Markets::withCount('projects')
            ->where('market_name',$_GET['market'])
            ->first();

        $totalRecordswithFilter = Markets::where('market_name',$_GET['market'])
            ->first()
            ->projects()
            ->select('count(*) as allcount')
            ->where('status_app','like', '%' .$columnName_arr[6]['search']['value']. '%')
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('package', 'like', '%' . $searchValue . '%')
                    ->orWhere('title_app', 'like', '%' . $searchValue . '%');
            })
            ->count();
        $records = Markets::where('market_name',$_GET['market'])
            ->first()
            ->projects()
            ->orderBy($columnName, $columnSortOrder)
            ->where('status_app','like', '%' .$columnName_arr[6]['search']['value']. '%')
            ->where(function($q) use ($searchValue) {
                $q->Where('projectname', 'like', '%' . $searchValue . '%')
                    ->orWhere('package', 'like', '%' . $searchValue . '%')
                    ->orWhere('title_app', 'like', '%' . $searchValue . '%');
            })
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


//            '<p class="card-title-desc font-16"><img src="img/icon/'.$market->market_logo.'"><a id="app_link_'.$market->pivot->id.'" href="'.$market->pivot->app_link.'"  target="_blank"> '.$market->pivot->package.'</a>'.$ads_status.'</p>';

//            $package = 'Package: <span class="text-muted" style="line-height:0.5"> '.$record->pivot->package.' </span>';

            $package = 'Package: <a id="app_link_'.$record->pivot->id.'" href="'.$record->pivot->app_link.'"  target="_blank"> <span class="text-muted" style="line-height:0.5"> '.$record->pivot->package.' </span></a>';
            $keystore = 'Key:<span class="text-muted" style="line-height:0.5"> '.$record->pivot->sdk.' </span>';
            $sdk = 'SDK : <span class="text-muted" style="line-height:0.5"> '.$record->pivot->keytore.' </span>';


            $status = $record->pivot->status_app;
            $console = $record->buildinfo_console;
            $Chplay_status =  '<span data-package="'.$record->pivot->id.'" class="check_Status_'.$record->pivot->pivotParent->market_name.' badge badge-';
            switch ($status){
                case 1:
                    $Chplay_status .=  'success "> Publish';
                    break;
                case 2:
                    $Chplay_status .=  'warning "> Suppend';
                    break;
                case 3:
                    $Chplay_status .=  'info"> UnPublish';
                    break;
                case 4:
                    $Chplay_status .=  'primary"> Remove';
                    break;
                case 5:
                    $Chplay_status .=  'dark"> Reject';
                    break;
                case 6:
                    $Chplay_status .=  'danger"> Check ';
                    break;
                case 7:
                    $Chplay_status .=  'warning"> Pending';
                    break;
                default:
                    $Chplay_status .=  'secondary"> M???c ?????nh';
                    break;
            }
            $Chplay_status .=  '</span>';

            switch ($console){
                case 1:
                    $buildinfo_console = '<span class="badge badge-dark">Build App</span>';
                    break;
                case 2:
                    $buildinfo_console =  '<span class="badge badge-warning">??ang x??? l?? Build App</span>';
                    break;
                case 3:
                    $buildinfo_console =  '<span class="badge badge-info">K???t th??c Build App</span>';
                    break;
                case 4:
                    $buildinfo_console =  '<span class="badge badge-primary">Check Data Project</span>';
                    break;
                case 5:
                    $buildinfo_console =  '<span class="badge badge-success">??ang x??? l?? check d??? li???u c???a Project</span>';
                    break;
                case 6:
                    $buildinfo_console =  '<span class="badge badge-danger">K???t th??c Check</span>';
                    break;
                case 7:
                    $buildinfo_console =  '<span class="badge badge-danger">Build App (Th???t b???i)</span>';
                    break;
                case 8:
                    $buildinfo_console =  '<span class="badge badge-danger">K???t th??c (D??? li???u thi???u) </span>';
                    break;
                default:
                    $buildinfo_console =  ' <span class="badge badge-secondary">Tr???ng th??i t??nh</span> ';
                    break;
            }

            $version_bot    = $record->pivot->bot_appVersion;
            $version_build  = $record->buildinfo_verstr;
            if($version_bot == $version_build ){
                $version = 'Version: <span class="badge badge-success">'.$version_bot.'</span>';
            }else{
                $version = 'Version Bot:  <span class="badge badge-danger">'.$version_bot.'</span> ' .  ' <br> Version Build:   <span class="badge badge-secondary">'.$version_build.'</span> ';
            }

            $data_arr[] = array(
                "logo" => $logo,
                "projectname"=>$project.$template.$mada.'<br>'.$record->title_app.'<br>'.$package.'<br>'.$sdk.'<br>'.$keystore,
                "bot_installs" => $record->pivot->bot_installs,
                "bot_numberVoters" => $record->pivot->bot_numberVoters,
                "bot_numberReviews" =>$record->pivot->bot_numberReviews,
                "bot_score" => $record->pivot->bot_score,
                "bot_appVersion" => $record->pivot->bot_appVersion,
                "status_app" =>'Console: '.$buildinfo_console.'<br> ???ng d???ng: '.$Chplay_status.'<br>'.@$version. '<br> Time check: '.date('H:i:s   d-m-Y',$record->pivot->bot_time),
                "buildinfo_console"=> $record->buildinfo_console,
                "action"=> $btn
            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords->projects_count,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);

    }

    public function copyProject(Request $request){


//        $rules = [
//            'project_name_copy' =>'required|unique:ngocphandang_project,projectname',
//        ];
//        $message = [
//            'project_name_copy.unique'=>'T??n Project ???? t???n t???i',
//        ];
//        $error = Validator::make($request->all(),$rules, $message );
//
//        if($error->fails()){
//            return response()->json(['errors'=> $error->errors()->all()]);
//        }



//        dd($request->all());
        $project_origin = Project::findorfail($request->project_id_origin);
//        dd($project_origin);
        $new_project =  $project_origin->duplicate();
//        $new_project =  $project_origin->replicate();
        $new_project->projectname =  $request->projectname;
        $new_project->push();
        dd($new_project);
//        $new_project->relations = [];
        $new_project->load('markets','lang');

        dd($new_project->relations,$new_project);

        foreach ($new_project->relations as $relationName => $values){

            dd($values,$relationName,12);
            $new_project->{$relationName}()->sync($values);
//            $new_project->lang()->sync($values);
        }
        dd($new_project,1);

//        dd($new_project);
//        $new_project->projectname = $request->project_name_copy;
        $new_project->save();




        dd($new_project,12);
        dd($request->all());
    }

    public function getProject($id){
        $project = Project::where('projectname',$id)->first();
        if (isset($project)){
            return response()->json(['msg'=>'success','data'=>$project->load('da','markets')]);
        }else{
            return response()->json(['msg'=>'error']);
        }

    }

}
