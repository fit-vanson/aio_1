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
//                'Create'            => ['id'=>'createNewApkUpload','style'=>'primary'],
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
            $logo =  '<img width="60px" height="60px" src="data:image/jpeg;base64,'.$record->logo_base64.'">';

            $data_arr[] = array(
                "logo" => $logo,
                "name" => $record->name,
                "filename" => $record->filename,
                "manifest_base64" => $record->manifest_base64 ? '<span style="font-size: 100%" id="show_manifest_base64" data-id="'.$record->id.'" class="badge badge-success"><i class="ion ion-md-checkmark"></i></span>': '<span style="font-size: 100%" class="badge badge-danger"><i class="ion ion-md-close"></span>',
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

        if($request->file){
            $folder = uniqid();
            $path = storage_path('app/public/apkupload/'.$folder.'/');
            if (!file_exists($path)) {
                mkdir($path, 777, true);
            }
            $file = $request->file;
            $file_name = $file->getClientOriginalName();
            $file->move($path, $file_name);

            $data = new ApkUploadAnalysis();
            $data->name = $file_name;
            $data->filename = $folder;
            $data->save();
        }
        return response()->json(['success'=>'Thành công']);
    }

    public function show_manifest_base64($id){
        $manifest = ApkUploadAnalysis::find($id);
        $manifest_base64 = base64_decode($manifest->manifest_base64);
        return response()->json($manifest_base64);
    }
}
