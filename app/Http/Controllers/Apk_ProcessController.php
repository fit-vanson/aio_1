<?php

namespace App\Http\Controllers;

use App\Models\Apk_Process;
use App\Models\Market_category;

use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//use Elastica\Client as ElaticaClient;



class Apk_ProcessController extends Controller

{

//    public function index(Request $request,$id,$cate_id){
    public function index(Request $request){


//        dd($request->all());

        if (isset($request->category) || isset($request->type)){

            $apk_process = Apk_Process::where('category',$request->category)->paginate(10);
            $categories = Market_category::where('id',$request->category)->first();
            return view('apk_process.index',compact(['apk_process','categories']));
        }
        if(isset($request->pss_console)){
            return view('apk_process.success');
        }
    }

    public function success(){
        return view('apk_process.success');
    }

    public function getIndex(Request $request)
    {
//        dd($request->all());
        ini_set('max_execution_time', -1);
        $draw = $request->get('draw');
        $start = $request->get("start");
//        $rowperpage = 10; // total number of rows per page
        $rowperpage = $request->get("length"); // total number of rows per page
//
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

            // Total records

        if(isset($request->category)){
            $totalRecords = Apk_Process::select('count(*) as allcount')->where('category',$request->category)->count();
            $totalRecordswithFilter = Apk_Process::select('count(*) as allcount')->where('category',$request->category)->count();
            $records = Apk_Process::orderBy($columnName, $columnSortOrder)
                ->where('category',$request->category)
//                ->paginate(10);
                ->skip($start)
                ->take($rowperpage)
                ->get();
            foreach ($records as $record)
            {
                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteApk_process"><i class="ti-trash"></i></a>';

                $logo = '<button type="button" class="btn waves-effect button" data-original-title="'.$record->id.'" >'.$record->id.' </button>
                                       <a href="'.$record->download.'" target="_blank" ><img class="rounded mx-auto d-block" height="100px" src="'.$record->icon.'"></a>
                                       <br>
                                       <p class="text-muted" style="line-height:0.5; text-align: center">'.Carbon::parse($record->upptime)->format('Y-m-d').'</p>';

                $screenshots = explode(';',$record->screenshot);

                foreach ($screenshots as $sc){
                    $img = '<img class="rounded mr-2 mo-mb-2" alt="200x200" style="height:100px  " src="'.$sc.'" data-holder-rendered="true">';
                }

                $data_arr[] = array(
                    "id" => $record->id,
                    'pss_console' => $record->pss_console,
                    'icon' => $logo,
                    "screenshot" => $img,
                    "description" => '<button type="button" class="btn waves-effect button" style="text-align: left" data-toggle="tooltip" data-original-title="'.$record->description.'" data-placement="left" data-container="body">'.substr($record->description,0,300).'</button>',
//                    "description" => '<button type="button" class="btn waves-effect button" style="text-align: left" data-toggle="tooltip"  data-original-title="'.$record->description.'" data-placement="left" data-container="body">'.strlen($record->description) > 300 ? substr($record->description,0,300)." ..." : $record->description.'</button>',
                    "action" =>$btn
                );
            }
        }

        else{
            $totalRecords = Apk_Process::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Apk_Process::select('count(*) as allcount')->where('pss_console',3 )->count();
            $records = Apk_Process::orderBy($columnName, $columnSortOrder)
                ->where('pss_console',3)

                ->skip($start)
                ->take($rowperpage)
                ->get();
            $data_arr = array();
            foreach ($records as $record)
            {
                // Title, Package, pss_sdk, vercode, verStr


                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteApk_process"><i class="ti-trash"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$record->id.'" data-original-title="check" class="btn btn-success actionApk_process"><i class="ion ion-md-checkmark-circle-outline"></i></a>';
                $ads = json_decode($record->pss_ads,true);
                $data_arr[] = array(
                    "id" => $record->id,
                    'pss_console' => $record->pss_console,
                    'icon' => '<button type="button" class="btn waves-effect button" data-original-title="'.$record->id.'">'.$record->id.'</button><span class="text-muted"  style="text-align: left;">Pss sdk: '.$record->pss_sdk.'</span><a href="'.$record->download.'" target="_blank" ><img class="rounded mx-auto d-block" height="100px" src="'.$record->icon.'"></a><br><p class="text-muted" style="line-height:0.5; text-align: center">'.\Carbon\Carbon::parse($record->upptime)->format('Y-m-d').'</p>',
                    "appid" => $record->appid,
//                    "title" =>
//                        '<p class="card-title"  style="text-align: left;">Title: '.$record->title.'</p><br>'.
//                        '<p class="text-muted"  style="text-align: left;">Package: '.$record->package.'</p><br>'.
//                        '<p class="text-muted"  style="text-align: left;">Vercode: '.$record->vercode.'</p><br>'.
//                        '<p class="text-muted"  style="text-align: left;">VerStr: '.$record->verStr.'</p><br>'.
//                        '<p class="text-muted"  style="text-align: left;">Pss Lib: '.$record->pss_lib.'</p><br>',

                    "title" =>'<ul>
                                    <li>Title: '.$record->title.'</li>
                                    <li>Package: '.$record->package.'</li>
                                    <li>Vercode: '.$record->vercode.'</li>
                                    <li>VerStr: '.$record->verStr.'</li>
                                    <li>Pss Lib: '.$record->pss_lib.'</li>
                                </ul>',
                    "pss_ads_str" => '<p class="card-title"  style="text-align: left;word-wrap: break-word ">'.$record->pss_ads_str.'</p>',
                    "pss_ads->Admob" => $record->pss_ads ? $ads['Admob'] ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Facebook" =>  $record->pss_ads ? $ads['Facebook']  ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->StartApp" => $record->pss_ads ? $ads['StartApp']  ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Huawei" => $record->pss_ads ?$ads['Huawei']   ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Iron" => $record->pss_ads ? $ads['Iron'] ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Applovin" =>  $record->pss_ads ? $ads['Applovin'] ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Appbrain" => $record->pss_ads ?$ads['Appbrain'] ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',
                    "pss_ads->Unity3d" => $record->pss_ads ?  $ads['Unity3d']  ?  '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>' : '',

                    "pss_aab" => $record->pss_aab != 0 ? '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>',
                    "pss_rebuild" => $record->pss_rebuild != 0 ? '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>',
                    "pss_lauch" => $record->pss_lauch != 0 ? '<span class="badge badge-success"><i class="mdi mdi-check"></i></span>':  '<span class="badge badge-danger"><i class="mdi mdi-close"></i></span>',

                    "action" =>$btn
                );
            }

        }


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        return json_encode($response);
    }


    public function delete($id){
        $apk = Apk_Process::find($id);
        $apk->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function update_pss($id){
        $apk = Apk_Process::find($id);
        $apk->pss_console = 1;
        $apk->save();
        return response()->json(['success'=>'Cập nhật thành công.']);
    }

}
