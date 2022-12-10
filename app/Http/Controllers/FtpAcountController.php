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
            ->orwhere('ftp_name', 'like', '%' . $searchValue . '%')
            ->orwhere('ftp_account', 'like', '%' . $searchValue . '%')

            ->count();
        // Get records, also we have included search filter as well
        $records = FtpAccount::orderBy($columnName, $columnSortOrder)
            ->Where('ftp_server', 'like', '%' . $searchValue . '%')
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
                "ftp_server" => $record->ftp_server.':'.$record->port ,
                "ftp_name" => $record->ftp_name,
                "ftp_account" => $record->ftp_account,
                "ftp_password" => $record->ftp_password,
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

    public function create(Request  $request)
    {
        $data = new FtpAccount();
        $data->ftp_name = $request->ftp_name;
        $data->ftp_server = $request->ftp_server;
        $data->port = $request->ftp_port;
        $data->ftp_account = $request->ftp_account;
        $data->ftp_password = $request->ftp_password;
        $data->ftp_note = $request->ftp_note;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);
    }

    public function checkConnect(Request $request){
        $host    = $request->ftp_server;
        $username = $request->ftp_account;
        $password = $request->ftp_password;
        $port = $request->ftp_port;
        try {
            $ftp = new \FtpClient\FtpClient();
            $ftp->connect($host, false, $port);
            $ftp->login($username, $password);

            if($request->ftp_id){
                $this->update($request);
            }else{
                $this->create($request);
            }
            return response()->json(['success'=>'Kết nối thành công']);
        }catch (\Exception $exception) {
            return response()->json(['error'=>'Kết nối thất bại. Vui lòng kiểm tra lại']);
        }
    }

    public function edit($id){
        $ftp_account = FtpAccount::find($id);
        return response()->json($ftp_account);
    }

    public function show($id){
        $local_file = 'local.zip';
        $server_file = '151.zip';
        $account = FtpAccount::find($id);


        $ftp_server = $account->ftp_server;
        $ftp_conn = ftp_connect($ftp_server,$account->port) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $account->ftp_account, $account->ftp_password);
        $target_dir = ".";
        $files = ftp_nlist($ftp_conn, $target_dir);

        $files = ftp_nlist($ftp_conn, $target_dir);
        $ftp_account = base64_encode($account->ftp_account);
        $ftp_password = base64_encode($account->ftp_password);
//        dd($account->ftp_password,$ftp_password);
        $a = '';
        foreach($files as $file) {
            if(!is_dir($file)){
                $a .= "<a href=\"../download?server=$account->ftp_server&port=$account->port&account=$ftp_account&password=$ftp_password&file=".urlencode($file)."\">".htmlspecialchars($file)."</a>";
                $a .="<br>";

            }else{
                dd(1);

            }


        }
        echo ($a);
        ftp_close($ftp_conn);
        return ;


//        '<a href="ftp://download.example.com/file.pdf">Download</a>'
//        'ftp://username:password@download.example.com/file.pdf'

        $a = '<a href="ftp://'.$account->ftp_account.':'.$account->ftp_password.'@'.$account->ftp_server.':'.$account->port.'/151.zip">Download</a>';
        echo $a;
        dd($a);


        $conn_id = ftp_connect($account->ftp_server,$account->ftp_server);
        $login_result = ftp_login($conn_id, $account->ftp_account, $account->ftp_password);
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
        } else {
            echo "There was a problem\n";
        }
        ftp_close($conn_id);
        dd($login_result);


        try {
            $ftp = new \FtpClient\FtpClient();
            $ftp->connect($account->ftp_server, false, $account->port);
            $ftp->login($account->ftp_account, $account->ftp_password);
            $list = $ftp->nlist();
            dd($list);
        }catch (\Exception $exception) {
            return response()->json(['error'=>'Kết nối thất bại. Vui lòng kiểm tra lại']);
        }
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

    public function update(Request $request){
        $data = FtpAccount::find($request->ftp_id);
        $data->ftp_name = $request->ftp_name;
        $data->ftp_server = $request->ftp_server;
        $data->port = $request->ftp_port;
        $data->ftp_account = $request->ftp_account;
        $data->ftp_password = $request->ftp_password;
        $data->ftp_note = $request->ftp_note;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        FtpAccount::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }
}
