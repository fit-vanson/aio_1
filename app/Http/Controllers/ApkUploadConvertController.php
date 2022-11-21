<?php

namespace App\Http\Controllers;

use App\Models\ApkUploadConvert;
use Illuminate\Http\Request;

class ApkUploadConvertController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Apk Upload Convert',

            'button' => [
//                'Create'            => ['id'=>'createNewApkUpload','style'=>'primary'],
//                'Build And Check'   => ['id'=>'build_check','style'=>'warning'],
//                'Status'            => ['id'=>'dev_status','style'=>'info'],
//                'KeyStore'          => ['id'=>'change_keystore','style'=>'success'],
//                'SDK'               => ['id'=>'change_sdk','style'=>'danger'],
//                'Upload Status'     => ['id'=>'change_upload_status','style'=>'secondary'],
            ]

        ];
        return view('apk_upload_convert.index')->with(compact('header'));
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
        $totalRecords = ApkUploadConvert::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = ApkUploadConvert::select('count(*) as allcount')

            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('filename', 'like', '%' . $searchValue . '%')
            ->orWhere('package', 'like', '%' . $searchValue . '%')
            ->orWhere('appname', 'like', '%' . $searchValue . '%')
            ->count();
        // Get records, also we have included search filter as well
        $records = ApkUploadConvert::orderBy($columnName, $columnSortOrder)
            ->Where('name', 'like', '%' . $searchValue . '%')
            ->orWhere('filename', 'like', '%' . $searchValue . '%')
            ->orWhere('package', 'like', '%' . $searchValue . '%')
            ->orWhere('appname', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
            $logo =  '<img width="60px" height="60px" src="'.url('/storage/apkconvert/'.$record->filename.'/lg.png').'">';

            switch ($record->process){
                case 1:
                    $process =  '<span class="badge badge-warning">Đang xử lý</span>';
                    break;
                case 2:
                    $process =  '<span class="badge badge-success">Done (OK)</span>';
                    break;
                case 3:
                    $process =  '<span class="badge badge-danger">Done (Fail)</span>';
                    break;

                default:
                    $process =  '<span class="badge badge-secondary">Chưa xử lý</span>';
                    break;
            }

            $data_arr[] = array(
                "id" => $record->id,
                "logo" => $logo,
                "name" => $record->name,
                "process" => $process,
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

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        if($request->file){
            $folder = uniqid();
            $path = storage_path('app/public/apkconvert/'.$folder.'/');
            if (!file_exists($path)) {
                mkdir($path, 777, true);
            }
            $file = $request->file;
            $file_name = $file->getClientOriginalName();
            $file->move($path, $file_name);

            $data = new ApkUploadConvert();
            $data->name = $file_name;
            $data->filename = $folder;
            $data->save();
        }
        return response()->json(['success'=>'Thành công']);
    }
}
