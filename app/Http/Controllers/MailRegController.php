<?php

namespace App\Http\Controllers;

use App\Models\MailReg;
use Illuminate\Http\Request;

class MailRegController extends Controller
{
    public function index()
    {
        return view('mailreg.index');
    }

    /* Process ajax request */
    public function getMailRegs(Request $request)
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
        $totalRecords = MailReg::select('count(*) as allcount')->count();
        $totalRecordswithFilter = MailReg::select('count(*) as allcount')
            ->leftjoin('ngocphandang_parent','ngocphandang_parent.user','=','ngocphandang_gmailreg.mailrecovery')
            ->where('ngocphandang_gmailreg.gmail', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.mailrecovery', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.ho', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.ten', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.birth', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.timereg', 'like', '%' . $searchValue . '%')
            ->select('ngocphandang_gmailreg.*',
                'ngocphandang_parent.user',
                'ngocphandang_parent.pass as parent_pass',
                'ngocphandang_parent.mailrecovery as parent_mailrecovery',
                'ngocphandang_parent.phone'
            )
            ->count();
        // Get records, also we have included search filter as well
        $records = MailReg::orderBy($columnName, $columnSortOrder)
            ->leftjoin('ngocphandang_parent','ngocphandang_parent.user','=','ngocphandang_gmailreg.mailrecovery')
            ->where('ngocphandang_gmailreg.gmail', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.mailrecovery', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.ho', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.ten', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.birth', 'like', '%' . $searchValue . '%')
            ->orWhere('ngocphandang_gmailreg.timereg', 'like', '%' . $searchValue . '%')
            ->select('ngocphandang_gmailreg.*',
                'ngocphandang_parent.user',
                'ngocphandang_parent.pass as parent_pass',
                'ngocphandang_parent.mailrecovery as parent_mailrecovery',
                'ngocphandang_parent.phone'
            )
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach ($records as $record) {
            $data_arr[] = array(
                "gmail" => $record->gmail,
                "pass" => $record->pass,
                "mailrecovery" => $record->mailrecovery,
                "parent_pass" => $record->parent_pass,
                "phone" => $record->phone,
                "parent_mailrecovery" => $record->parent_mailrecovery,
                "hovaten" => $record->ho. ' '. $record->ten,
                "birth" => $record->birth .'  |  '.date('d-m-Y',$record->timereg),
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
