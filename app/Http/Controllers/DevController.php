<?php

namespace App\Http\Controllers;

use App\Models\Dev;

use App\Models\Ga;
use App\Models\Ga_dev;
use App\Models\Profile;
use App\Models\ProfileV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class DevController extends Controller
{
    public function index()
    {

        $header = [
            'title' => 'Dev',

            'button' => [
                'Create'            => ['id'=>'createNewDev','style'=>'primary'],
            ]

        ];
        return view('dev.index')->with(compact('header'));

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
        $totalRecords = Dev::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = Dev::select('count(*) as allcount')
            ->where(function($q) use ($searchValue) {
                $q->Where('store_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('dev_name', 'like', '%' . $searchValue . '%')
                    ->orwhereHas('gmail_dev2', function ($query) use ($searchValue) {
                        return $query->Where('gmail', 'like', '%' . $searchValue . '%');
                    })
                    ->orwhereHas('gmail_dev1', function ($query) use ($searchValue) {
                        return $query->Where('gmail', 'like', '%' . $searchValue . '%');
                    });
            })
            ->Where('market_id','like', '%' .$columnName_arr[6]['search']['value']. '%')
            ->Where('status','like', '%' .$columnName_arr[7]['search']['value']. '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Dev::orderBy($columnName, $columnSortOrder)
            ->where(function($q) use ($searchValue) {
                $q->Where('dev_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('store_name', 'like', '%' . $searchValue . '%')
                    ->orwhereHas('gmail_dev1', function ($query) use ($searchValue) {
                        return $query->Where('gmail', 'like', '%' . $searchValue . '%');
                    })
                    ->orwhereHas('gmail_dev2', function ($query) use ($searchValue) {
                        return $query->Where('gmail', 'like', '%' . $searchValue . '%');
                    });
            })
            ->Where('market_id','like', '%' .$columnName_arr[6]['search']['value']. '%')
            ->Where('status','like', '%' .$columnName_arr[7]['search']['value']. '%')

            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {

            $btn = ' <a href="javascript:void(0)" onclick="editDev('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)"data-id="'.$record->id.'"  class="btn btn-danger deleteDev"><i class="ti-trash"></i></a>';
            if($record->market_id == 1 && $record->api_client_secret){
                $btn = $btn.' <a  href="/api/get-token/'.$record->id.'" target="_blank" class="btn btn-info"><i class="mdi mdi-key"></i></a>';
                if($record->api_token){
                    $btn = $btn.' <a  href="/api/getReview/'.$record->id.'" target="_blank" class="btn btn-secondary"><i class="mdi mdi-emoticon-kiss-outline"></i></a>';
                }
            }



            if($record->info_logo == null ){
                $logo =  '<img width="60px" height="60px" src="assets\images\logo-member.jpg">';
            }else{
                $logo =  '<img width="60px" height="60px" src='.$record->logo.'>';
            }


            if($record->ga_id == null   ){
                $ga_name =  '<span class="badge badge-dark">Ch??a c??</span>';
            }else{
                $ga_name =$record->ga->ga_name;
            }
            if($record->ga_id == 0   ){
                $profile =  '<span class="badge badge-dark">Ch??a c??</span>';
            }else{
                $profile ='<span class="badge badge-info">'.@$record->profile->profile_name.'</span>'.@$record->profile->profile_ho_va_ten;
            }

            switch ($record->status){
                case 1:
                    $status = '<span class="badge badge-primary">??ang ph??t tri???n</span>';
                    break;
                case 2:
                    $status = '<span class="badge badge-warning">????ng</span>';
                    break;
                case 3:
                    $status = '<span class="badge badge-danger">Suspend</span>';
                    break;
                default:
                    $status =  '<span class="badge badge-dark">Ch??a x??? d???ng</span>';
                    break;
            }

            if($record->url_dev !== Null){
                $info_url = '<a  target= _blank href="'.$record["info_url"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_url = "<i style='color:red;' class='ti-close h5'></i>";
            }
            if($record->url_fanpage !== Null){
                $info_fanpage = '<a  target= _blank href="'.$record["info_fanpage"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_fanpage= "<i style='color:red;' class='ti-close h5'></i>";
            }
            if($record->url_policy !== Null){
                $info_policydev = '<a  target= _blank href="'.$record["info_policydev"].'" <i style="color:green;" class="ti-check-box h5"></i></a>';
            } else {
                $info_policydev = "<i style='color:red;' class='ti-close h5'></i>";
            }

            if($record->company_pers == 0){
                $company_pers = '<img height="70px" src="img/icon/person.png">';
            }else{
                $company_pers = '<img height="70px" src="img/icon/company.png">';
            }

            if($record->gmail_dev1){
                $gmail = '<span>'.$record->gmail_dev1->gmail.' - <span style="font-style: italic"> '.$record->gmail_dev1->vpn_iplogin.'</span></span>';
            }
            if($record->gmail_dev2){
                $gmail1 = '<p style="margin: auto" class="text-muted ">'.$record->gmail_dev2->gmail.' - <span style="font-style: italic"> '.$record->gmail_dev2->vpn_iplogin.'</span></p>';
            }

            $store_name = '<p style="margin: auto" class="text-muted ">'.$record->store_name.'</p>';


            $data_arr[] = array(
                "id" => $record->id,
                "ga_id" => $logo.'<br>'.$ga_name.' <br>'.$profile,
                "dev_name" => $record->dev_name.$store_name,
                "mail_id_1"=> @$gmail.@$gmail1,
                "market_id"=> @$record->markets->market_name,
//                "project_count" => ' <span class="badge badge-secondary">'.count($record->project).'</span> ' .' <span class="badge badge-success"> '.$release.' </span>'. ' <span class="badge badge-danger"> '.$check.' </span>' ,
                "company_pers" => $company_pers.'<br>'.$record->pass .'<br>'.$record->phone,
                "info_url"=> $info_url .' '. $info_fanpage.' '. $info_policydev,
                "status"=> $status,
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


//        'column_1' => 'required|unique:TableName,column_1,' . $this->id . ',id,colum_2,' . $this->column_2

        $rules = [
            'store_name' =>'unique:market_devs,store_name',
            'dev_name' =>'unique:market_devs,dev_name',

        ];
        $message = [
            'dev_name.unique'=>'Dev name ???? t???n t???i',
            'store_name.unique'=>'Store name t???n t???i',


        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Dev();
        $data['dev_name'] = $request->dev_name;
        $data['ga_id'] = $request->ga_id;
        $data['market_id'] = $request->market_id;
        $data['store_name'] = $request->store_name;
        $data['mail_id_1'] = $request->mail_id_1;
        $data['mail_id_2'] = $request->mail_id_2;
        $data['mahoadon'] = $request->mahoadon;
        $data['pass'] = $request->pass;
        $data['logo'] = $request->info_logo;
        $data['banner'] = $request->info_banner;
        $data['url_policy'] = $request->info_policydev;
        $data['url_fanpage'] = $request->info_fanpage;
        $data['phone'] = $request->info_phone;
        $data['status'] = $request->status;
        $data['profile_id'] = $request->profile_id;
        $data['company_pers'] = $request->attribute;
        $data['dev_id'] = $request->api_dev_id;
        $data['api_client_id'] = $request->api_client_id;
        $data['api_client_secret'] = $request->api_client_secret;
        $data['api_token'] = $request->api_token;
        $data['api_access_key'] = $request->api_access_key;
        $data['note'] = $request->note;
        $data->save();
        return response()->json([
            'success'=>'Th??m m???i th??nh c??ng',
        ]);
    }
    public function edit($id)
    {
        $dev = Dev::find($id);
        return Response::json($dev->load('ga','markets','gmail_dev1','gmail_dev2','profile'));
    }
    public function update(Request $request)
    {
        $id = $request->dev_id;
        $rules = [
            'store_name' =>'unique:market_devs,store_name,'.$id.',id',
            'dev_name' =>'unique:market_devs,dev_name,'.$id.',id',

        ];
        $message = [
            'dev_name.unique'=>'Dev name ???? t???n t???i',
            'store_name.unique'=>'Store name t???n t???i',

        ];

        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Dev::find($id);
        $data['dev_name'] = $request->dev_name;
        $data['ga_id'] = $request->ga_id;
        $data['market_id'] = $request->market_id;
        $data['store_name'] = $request->store_name;
        $data['mail_id_1'] = $request->mail_id_1;
        $data['mail_id_2'] = $request->mail_id_2;
        $data['mahoadon'] = $request->mahoadon;
        $data['pass'] = $request->pass;
        $data['logo'] = $request->info_logo;
        $data['banner'] = $request->info_banner;
        $data['url_policy'] = $request->info_policydev;
        $data['url_fanpage'] = $request->info_fanpage;
        $data['phone'] = $request->info_phone;
        $data['status'] = $request->status;
        $data['profile_id'] = $request->profile_id;
        $data['company_pers'] = $request->attribute;
        $data['dev_id'] = $request->api_dev_id;
        $data['api_client_id'] = $request->api_client_id;
        $data['api_client_secret'] = $request->api_client_secret;
        $data['api_token'] = $request->api_token;
        $data['api_access_key'] = $request->api_access_key;
        $data['note'] = $request->note;

        $data->save();
        return response()->json(['success'=>'C???p nh???t th??nh c??ng']);
    }
    public function delete($id)
    {
        Dev::find($id)->delete();
        return response()->json(['success'=>'X??a th??nh c??ng.']);
    }
    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }


    public function change($id)
    {

        $dev  = Dev::find($id);
        $status = $dev->status;
        switch ($status){
            case 0:
                $dev->status = 1;
                break;
            case 1:
                $dev->status = 2;
                break;
            case 2:
                $dev->status = 3;
                break;
            case 3:
                $dev->status = 0;
                break;
        }
        $dev->save();
        return response()->json(['success'=>'Th??nh c??ng.','dev'=>$dev]);
    }

    public function checkProject(){

    }

}
