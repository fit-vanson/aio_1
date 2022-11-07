<?php

namespace App\Http\Controllers;

use App\Models\ApkUploadAnalysis;
use Illuminate\Http\Request;

class ApkUploadAnalysisController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Apk Upload Analysis',

            'button' => [
                'Create'            => ['id'=>'createNewApkUpload','style'=>'primary'],
//                'Build And Check'   => ['id'=>'build_check','style'=>'warning'],
//                'Status'            => ['id'=>'dev_status','style'=>'info'],
//                'KeyStore'          => ['id'=>'change_keystore','style'=>'success'],
//                'SDK'               => ['id'=>'change_sdk','style'=>'danger'],
//                'Upload Status'     => ['id'=>'change_upload_status','style'=>'secondary'],
            ]

        ];
        return view('apk_upload.index')->with(compact('header'));
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
        $totalRecords = ApkUploadAnalysis::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = ApkUploadAnalysis::select('count(*) as allcount')

            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('filename', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = ApkUploadAnalysis::orderBy($columnName, $columnSortOrder)
            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('filename', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $logo =  '<img width="60px" height="60px" src="'.url('/storage/apkupload/'.$record->filename.'/lg.png').'">';
            $data_arr[] = array(
                "logo" => $logo,
                "name" => $record->name,
                "filename" => $record->filename,

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
