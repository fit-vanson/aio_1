<?php

namespace App\Http\Controllers;

use App\Models\FtpAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FtpAcountController extends Controller
{
    public function index()
    {

        $header = [
            'title' => 'FTP Account',
            'button' => [
                'Create'            => ['id'=>'createNew','style'=>'primary'],
            ]
        ];
        return view('ftp_account.index')->with(compact('header'));
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
        $totalRecords = FtpAccount::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = FtpAccount::select('count(*) as allcount')
            ->Where('ftp_server', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_server_internal', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_name', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_account', 'like', '%' . $searchValue . '%')

            ->count();
        // Get records, also we have included search filter as well
        $records = FtpAccount::orderBy($columnName, $columnSortOrder)
            ->Where('ftp_server', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_server_internal', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_name', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_account', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning btn-sm editFtpAccount"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)"  data-id="'.$record->id.'" class="btn btn-danger btn-sm deleteFtpAccount"><i class="ti-trash"></i></a>';
//            $btn = $btn.' <a href="javascript:void(0)"  data-id="'.$record->id.'" class="btn btn-info btn-sm viewFtpAccount"><i class="ti-eye"></i></a>';
            $btn = $btn.' <a taget="_blank" href="'.route('ftp_account.show',['id'=>$record->id]).'"  class="btn btn-info btn-sm viewFtpAccount"><i class="ti-eye"></i></a>';
            $data_arr[] = array(
//                "name" => '<a id="download" href="'.route('exiftool.downloadFile',"folder=$record->name").'" target="_blank">'.$record->name.'</a>',
                "ftp_server" => $record->ftp_server,
                "ftp_port" => $record->port ,
                "ftp_server_internal" => $record->ftp_server_internal ,
                "ftp_port_internal" => $record->ftp_port_internal ,
                "id" => $record->id,
                "ftp_name" => $record->ftp_name,
                "ftp_account" => $record->ftp_account,
                "ftp_password" => $record->ftp_password,
                "status" => $record->status,
                "status_internal" => $record->status_internal,
                "action" => $btn,

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

    public function create(Request  $request,$status =0,$status_internal=0)
    {
        $data = new FtpAccount();
        $data->ftp_name = $request->ftp_name;
        $data->ftp_server = $request->ftp_server;
        $data->port = $request->ftp_port;
        $data->ftp_account = $request->ftp_account;
        $data->ftp_password = $request->ftp_password;
        $data->ftp_note = $request->ftp_note;
        $data->ftp_server_internal = $request->ftp_server_internal;
        $data->ftp_port_internal = $request->ftp_port_internal;
        $data->status = $status;
        $data->status_internal = $status_internal;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }

    public function checkConnect(Request $request){

        $username = $request->ftp_account;
        $password = $request->ftp_password;

        $host    = $request->ftp_server;
        $port = $request->ftp_port;

        $host_internal    = $request->ftp_server_internal;
        $port_internal = $request->ftp_port_internal;

        try {
            $ftp = new \FtpClient\FtpClient();
            $ftp->connect($host, false, $port);
            $ftp->login($username, $password);
            $status = 1;


        }catch (\Exception $exception) {
            $status = 0;
        }

        try {
            $ftp = new \FtpClient\FtpClient();
            $ftp->connect($host_internal, false, $port_internal);
            $ftp->login($username, $password);
            $status_internal = 1;

        }catch (\Exception $exception) {
            $status_internal = 0;
        }

        if($request->ftp_id){
            $this->update($request,$status,$status_internal);
        }else{
            $this->create($request,$status,$status_internal);
        }
        return response()->json(['success'=>'Thành công']);
    }

    public function edit($id){
        $ftp_account = FtpAccount::find($id);
        return response()->json($ftp_account);
    }

    public function show($id){
        $account = FtpAccount::find($id);
        $server = \request()->server_ftp;
        $port = \request()->port;
        $acc = $account->ftp_account;
        $pass = $account->ftp_password;
        $dir = $_GET['folder']?? '/' ;

        $this->showFoder($server,$port,$acc,$pass, $dir);
        return ;
    }

    function showFoder($server,$port,$acc,$pass, $dir){
        echo '<br>The system type is: '.($dir).'<br>';

//        $ftp = new \FtpClient\FtpClient();
//        $ftp->connect($server, false, $port);
//        $ftp->login($acc, $pass);
//        dd($ftp->nlist());





        $ftpConn = ftp_connect($server,$port) or die("Could not connect to");
        ftp_login($ftpConn, $acc, $pass);
        ftp_set_option($ftpConn, FTP_USEPASVADDRESS, false);
        ftp_pasv($ftpConn, true);

        $lists = ftp_nlist($ftpConn, $dir);
//        $lists = $ftp->nlist($dir);
//        dd($lists);

        foreach($lists as $list) {
//        dd($ftp->rawlist($list));
//            $is_dir = $this->ftp_directory_exists($ftp->connect($server, false, $port),  $list);
//            dd($is_dir);
//            if($is_dir){
//                echo "<a href=\"?server_ftp=$server&port=$port&folder=".urlencode($list)."\">".htmlspecialchars($list)."</a>";
//                echo "<br>";
//            }else{
                echo "<a href=\"?server_ftp=$server&port=$port&action=download&file=".urlencode($list)."\">".htmlspecialchars($list)."</a>";
                echo "<br>";
//            }
        }
        $action = $_GET['action'] ?? null;
        switch ($action){
            case 'download':
                $file_path = $_GET["file"];

                $size = ftp_size($ftpConn, $file_path);
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . basename($file_path));
                header("Content-Length: $size");
                ftp_get($ftpConn, "php://output", $file_path, FTP_BINARY);



//                dd($file_path);
//                $file_url = "ftp://$acc:$pass@$server/$file_path";
//                header('Content-Type: application/octet-stream');
//                header("Content-Transfer-Encoding: Binary");
//                header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
//                readfile($file_url);
                break;
        }
        ftp_close($ftpConn);
        return true ;

    }

    function byteconvert($bytes) {

        $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $exp = floor( log($bytes) / log(1024) );

        return sprintf( '%.2f ' . $symbol[ $exp ], ($bytes / pow(1024, floor($exp))) );
    }

    function chmodnum($chmod) {
        $trans = array('-' => '0', 'r' => '4', 'w' => '2', 'x' => '1');
        $chmod = substr(strtr($chmod, $trans), 1);
        $array = str_split($chmod, 3);
        return array_sum(str_split($array[0])) . array_sum(str_split($array[1])) . array_sum(str_split($array[2]));
    }


    public function download(){
        $conn_id = ftp_connect($_GET['server'],$_GET['port']);
        $ftp_account = base64_decode($_GET['account']);
        $ftp_password = base64_decode($_GET['password']);

        ftp_login($conn_id, $ftp_account, $ftp_password);
        ftp_pasv($conn_id, true);

        $file_path = $_GET["file"];
        $size = ftp_size($conn_id, $file_path);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($file_path));
        header("Content-Length: $size");

        ftp_get($conn_id, "php://output", $file_path, FTP_BINARY);
        ftp_close($conn_id);
        return;
    }

    public function update(Request $request,$status =0,$status_internal=0){
        $data = FtpAccount::find($request->ftp_id);
        $data->ftp_name = $request->ftp_name;
        $data->ftp_server = $request->ftp_server;
        $data->port = $request->ftp_port;
        $data->ftp_account = $request->ftp_account;
        $data->ftp_password = $request->ftp_password;
        $data->ftp_note = $request->ftp_note;
        $data->ftp_server_internal = $request->ftp_server_internal;
        $data->ftp_port_internal = $request->ftp_port_internal;
        $data->status = $status;
        $data->status_internal = $status_internal;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        FtpAccount::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    function ftp_directory_exists($ftp, $dir)
    {
        // Get the current working directory
//        dd($ftp,$dir);
        $origin = ftp_pwd($ftp);

        // Attempt to change directory, suppress errors
        if (@ftp_chdir($ftp, $dir))
        {
            // If the directory exists, set back to origin
            ftp_chdir($ftp, $origin);
            return true;
        }

        // Directory does not exist
        return false;
    }
}
