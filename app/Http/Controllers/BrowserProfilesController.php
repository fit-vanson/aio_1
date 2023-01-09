<?php

namespace App\Http\Controllers;

use App\Models\Browser_Profiles;
use Illuminate\Http\Request;

class BrowserProfilesController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Project',
            'button' => []

        ];
        return view('browser_profiles.index')->with(compact('header'));
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
        $totalRecords = Browser_Profiles::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = Browser_Profiles::select('count(*) as allcount')
            ->where('profile_name_x', 'like', '%' . $searchValue . '%')
            ->orwhere('email', 'like', '%' . $searchValue . '%')
            ->orwhere('uuid', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Browser_Profiles::orderBy($columnName, $columnSortOrder)
            ->where('profile_name_x', 'like', '%' . $searchValue . '%')
            ->orwhere('email', 'like', '%' . $searchValue . '%')
            ->orwhere('uuid', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {

            $btn = '<div class="button-items">';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editBrowser_Profiles"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)" data-id="'.$record->projectid.'" data-original-title="Delete" class="btn btn-danger deleteBrowser_Profiles"><i class="ti-trash"></i></a>';


            $btn = $btn.'</div>';


            $data_arr[] = array(
                "id" => $record->id,
//                "profile_name_x" => $record->profile_name_x,
                "profile_name_x" => '<a href="#" data-pk="'.$record->id.'" data-action="profile_name_x" class="editable" data-url="">'.$record->profile_name_x.'</a>',
//                "uuid" =>  '<a href="javascript:void(0)" data-id="'.$record->id.'" class="download" data-url="">'.$record->uuid.'</a>',
                "uuid" =>  '<a href="/browser_profiles/download/'.$record->id.'">'.$record->uuid.'</a>',
                "email" => '<a href="#" data-pk="'.$record->id.'" data-action="email" class="editable" data-url="">'.$record->email.'</a>',
                "ipvpn" =>'<a href="#" data-pk="'.$record->id.'" data-action="ipvpn" class="editable" data-url="">'.$record->ipvpn.'</a>',
                "open" => $record->open,
                "pcname" => $record->pcname,
                "time_open" => gmdate('H:i:s d-m-Y',$record->time_open),
//                "note" => $record->note,
                "note" => '<a href="#" data-pk="'.$record->id.'" data-action="note" class="editable" data-url="">'.$record->note.'</a>',
                "action"=> $btn,

            );

        }
        //http://127.0.0.1:1238/browser_profiles/download/321

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    public function update($id){
        $browser_profiles = Browser_Profiles::find($id);
        $action = \request()->action;
        $value= \request()->value;

        $browser_profiles->update([$action=>$value]);
        return response()->json(['success'=>'Thành công']);

    }

    public function download($id){
        $browser_profiles = Browser_Profiles::find($id);

        $username = $browser_profiles->ftp_account->ftp_account;
        $password = $browser_profiles->ftp_account->ftp_password;

        if($browser_profiles->ftp_account->status_internal == 1){
            $host               = $browser_profiles->ftp_account->ftp_server_internal;
            $port               = $browser_profiles->ftp_account->ftp_port_internal;
        }elseif ($browser_profiles->ftp_account->status == 1){
            $host               = $browser_profiles->ftp_account->ftp_server;
            $port               = $browser_profiles->ftp_account->ftp_port;
        }else{
            return response()->json(['err'=>'Thành công']);
        }

        $conn_id = ftp_connect($host,$port);
        ftp_login($conn_id, $username, $password);
        ftp_pasv($conn_id, true);
        $file_path = '/browservmmo/'.$browser_profiles->ftp_folder.'/'.$browser_profiles->uuid.'.zip';
//        dd($file_path);
        $size = ftp_size($conn_id, $file_path);


        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($file_path));
        header("Content-Length: $size");

        ftp_get($conn_id, "php://output", $file_path, FTP_BINARY);
        ftp_close($conn_id);
        return response()->json(['success'=>'Thành công']);

    }
}
