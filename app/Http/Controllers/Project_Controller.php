<?php

namespace App\Http\Controllers;

use App\Models\Keystore;
use App\Models\Markets;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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
            ->with('markets','ma_template','da')
            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            if($record->buildinfo_console == 0){
                $btn = $btn. ' <br><br>  <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            }
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';

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

                    $status = $market->pivot->status_app;
                    switch ($status){
                        case 0:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-secondary font-16"> Mặc định</p> </div>';
                            break;
                        case 1:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-success font-16"> Publish</p></div>';
                            break;
                        case 2:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-warning font-16"> Suppend</p></div>';
                            break;
                        case 3:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-info font-16"> UnPublish</p></div>';
                            break;
                        case 4:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-primary font-16"> Remove</p></div>';
                            break;
                        case 5:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-dark font-16"> Reject</p></div>';
                            break;
                        case 6:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-danger font-16"> Check</p></div>';
                            break;
                        case 7:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-warning font-16"> Pending</p></div>';
                            break;
                        default:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-secondary font-16"> Mặc định</p></div>';
                            break;
                    }

                    if($market->pivot->sdk){
                        $sdk .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->sdk.' </span> ';
                    }
                    if($market->pivot->keystore){
                        $keystore .= ' <span class="badge badge-'.$badges[$key].'" style="font-size: 12px"> '.strtoupper($market->market_name[0]).': '.$market->pivot->keystore.' </span> ';
                    }

                }
            }

            $data_arr[] = array(
                "projectid" => $record->projectid,
                "logo" => $logo,
                "projectname"=>$project.$template.$mada.'<br>'.$record->title_app.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore,
                "markets"=>$package,
                "status"=>$status_app.$dev,
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
            'template.not_in'=>'Mã template không để trống',
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


        $inset_market = [];

        foreach ($request->market as $key=>$value){
            if($value['package']){
                $inset_market[$key] = [
                    'package' => $value['package'],
                    'dev_id' => $value['dev_id'],
                    'keystore' => $value['keystore'],
                    'sdk' => $value['sdk'],
                    'app_link' => $value['app_link'],
                    'policy_link' => $value['policy_link'],
                    'ads' => json_encode($value['ads']),
                    'app_name_x' => $value['app_name_x'],
                    'appID' => $value['appID'],
                    'video_link' => $value['video_link'],
                ];
            }
        }

        $data->save();
        $data->markets()->attach($inset_market);
        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id){
        $project = Project::find($id);
        return response()->json($project->load('markets','da','ma_template.markets'));
    }

    public function update(Request $request){

        $id = $request->project_id;
        $rules = [
            'projectname' =>'unique:ngocphandang_project,projectname,'.$id.',projectid',
//            'ma_da' => 'required',
//            'template' => 'required',
//            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
            'project_file' => 'mimes:zip',
        ];
        $message = [
            'projectname.unique'=>'Tên Project đã tồn tại',
            'projectname.required'=>'Tên Project không để trống',
//            'ma_da.required'=>'Mã dự án không để trống',
//            'template.required'=>'Mã template không để trống',
//            'title_app.required'=>'Tiêu đề ứng không để trống',
            'buildinfo_vernum.required'=>'Version Number không để trống',
            'buildinfo_verstr.required'=>'Version String không để trống',
            'project_file.mimes'=>'*.zip',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $data = Project::find($id)->load('markets');
        $data->template = $request->template ? $request->template : $data->template ;
        $data->ma_da = $request->ma_da ? $request->ma_da : $data->ma_da ;
        $data->title_app = $request->title_app;

        $data['buildinfo_link_fanpage'] = $request->buildinfo_link_fanpage;
        $data['buildinfo_link_website'] =  $request->buildinfo_link_website;

        $data['buildinfo_api_key_x'] = $request->buildinfo_api_key_x;
        $data['buildinfo_console'] = 0;
        $data['buildinfo_vernum' ]= $request->buildinfo_vernum;
        $data['buildinfo_verstr'] = $request->buildinfo_verstr;
        $data['data_onoff'] = $request->data_status ? (int)  $request->data_status :0;



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
        $inset_market = [];
        foreach ($request->market as $key=>$value){
            if($value['package']){
                $inset_market[$key] = [
                    'package' => $value['package'],
                    'dev_id' => $value['dev_id']   ,
                    'keystore' => $value['keystore'],
                    'sdk' => $value['sdk'],
                    'app_link' => $value['app_link'],
                    'policy_link' => $value['policy_link'],
                    'ads' => json_encode($value['ads']),
                    'app_name_x' => $value['app_name_x'],
                    'appID' => $value['appID'],
                    'video_link' => $value['video_link'],
                ];
            }
        }

        $data->save();
        $data->markets()->sync($inset_market);
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
        foreach ($data as $item){
            try {
                [$ID_Project, $Key_C, $Key_A, $Key_S, $Key_X, $Key_O, $Key_V, $Key_H ] = explode("|",$item);

                $store = [
                    $Key_C, $Key_A, $Key_S, $Key_X, $Key_O, $Key_V, $Key_H
                ];
                $insert = [];


                $project = Project::where('projectname',trim($ID_Project))->firstorfail()->load('markets');

                switch ($action){
                    case 'keystore':
                        foreach ($markets as $key=>$market){
                            $insert[$market] = ['keystore'=>$store[$key]];
                        }

                        $project->markets()->sync($insert);

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
                        break;
                    case 'sdk':
                        foreach ($markets as $key=>$market){
                            $insert[$market] = ['sdk'=>$store[$key]];
                        }
                        $project->markets()->sync($insert);
                        break;
                }


            }catch (\Exception $exception) {
                \Illuminate\Support\Facades\Log::error('Message:' . $exception->getMessage() . '--- chang key : ' . $exception->getLine());
            }
        }

        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function updateDevStatus(Request $request){
        $data = explode("\r\n",$request->project_data);
        $project = Project::whereIN('projectname',$data)->get();
        foreach ($project as $item){

            $data_item = [];
            foreach ($item->markets as $target) {
                $data_item[$target->pivot->market_id] =
                    [
                        'dev_id'=>$target->pivot->dev_id,
                        'status_app'=>$target->pivot->status_app
                    ];
            }
            $inset_market = [];

            foreach ($data_item as $key=>$value){
                $inset_market[$key] = [
                    'dev_id' => $request->market[$key]['dev_id'] == 0  ? $value['dev_id']  :   $request->market[$key]['dev_id'] ,
                    'status_app' => $request->market[$key]['status_app'] == 0 ? $value['status_app'] : $request->market[$key]['status_app'],
                ];
            }

            $item->markets()->sync($inset_market);
        }
        return response()->json(['success'=>'Cập nhật thành công ']);

    }

    public function process(Request $request)
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
}
