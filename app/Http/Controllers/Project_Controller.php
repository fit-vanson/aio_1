<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            ->with('markets','ma_template','da')
//            ->whereIN('projectid',[15,13,14,97])

            ->Where('projectname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//            ->get()->load('markets','ma_template','da');

        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="editProject('.$record->projectid.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="'.route('project.show',['id'=>$record->projectid]).'" target="_blank"  class="btn btn-secondary"><i class="ti-eye"></i></a>';
            if($record->buildinfo_console == 0){
                $btn = $btn. '   <a href="javascript:void(0)" onclick="quickEditProject('.$record->projectid.')" class="btn btn-success"><i class="mdi mdi-android-head"></i></a>';
            }
            $btn = $btn.'<br><br> <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteProject"><i class="ti-trash"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->projectid.'"  class="btn btn-info fakeProject"><i class="ti-info-alt"></i></a>';


            if(isset($record->logo)){
                $logo = '<img class="rounded mx-auto d-block"  width="100px"  height="100px"  src="'.url('storage/projects/'.$record->da->ma_da.'/'.$record->projectname.'/lg114.png').'">';
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
                    $package .= '<span style="color:black;line-height:0.5"><img src="img/icon/'.$market->market_logo.'"> '.$market->pivot->package.'</span> <button onclick="copyPackage(this)" type="button" data-text="'.$market->pivot->package.'" class="btn btn-link waves-effect"><i class="mdi mdi-content-copy"></i></button><br>';

                    $status = $market->pivot->status_app;
                    switch ($status){
                        case 0:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-secondary"> Mặc định</p> </div>';
                            break;
                        case 1:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-success"> Publish</p></div>';
                            break;
                        case 2:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-warning"> Suppend</p></div>';
                            break;
                        case 3:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-info"> UnPublish</p></div>';
                            break;
                        case 4:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-primary"> Remove</p></div>';
                            break;
                        case 5:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-dark"> Reject</p></div>';
                            break;
                        case 6:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-danger"> Check</p></div>';
                            break;
                        case 7:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-warning"> Pending</p></div>';
                            break;
                        default:
                            $status_app .=  '<div><img src="img/icon/'.$market->market_logo.'"> <p class="badge badge-secondary"> Mặc định</p></div>';
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
                "logo" => $logo,
                "projectname"=>$project.$template.'<br>'.$version.'<br>'.$sdk.'<br>'.$keystore,
                "markets"=>$package,
                "status"=>$status_app,
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
            'title_app' =>'required',
            'buildinfo_vernum' =>'required',
            'buildinfo_verstr' =>'required',
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
        $data['buildinfo_link_policy_x'] = $request->buildinfo_link_policy_x;
        $data['buildinfo_link_fanpage'] = $request->buildinfo_link_fanpage;
        $data['buildinfo_link_website'] =  $request->buildinfo_link_website;
        $data['buildinfo_link_youtube_x'] = $request->buildinfo_link_youtube_x;
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

//        dd($request->market);
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
                ];
            }
        }



        $data->save();
        $data->markets()->attach($inset_market);
        return response()->json(['success'=>'Thành công']);







    }

    public function edit($id){
        $project = Project::find($id);
        return response()->json($project);
    }
}
