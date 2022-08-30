<?php

namespace App\Http\Controllers;

use App\Models\DeviceInfo;
use Illuminate\Http\Request;

class DeviceInfoController extends Controller
{
    public function index()
    {
        return view('device_info.index');
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
        $totalRecords = DeviceInfo::select('count(*) as allcount')->count();
        $totalRecordswithFilter = DeviceInfo::select('count(*) as allcount')
            ->where('device_info.id', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.android', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.verrelease', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.sdk', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productmodel', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productbrand', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productname', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productmanufacturer', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = DeviceInfo::orderBy($columnName, $columnSortOrder)
            ->where('device_info.id', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.android', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.verrelease', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.sdk', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productmodel', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productbrand', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productname', 'like', '%' . $searchValue . '%')
            ->orWhere('device_info.productmanufacturer', 'like', '%' . $searchValue . '%')
            ->select('device_info.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();



        $data_arr = array();

        foreach ($records as $record) {
            $action = ' <a href="javascript:void(0)" onclick="editDevice(\''.$record->id.'\')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//            $action = ' <a href="javascript:void(0)" onclick="editDevice(\''.$record->id.'\')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $action = $action.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Delete" class="btn btn-danger deleteDevice"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "android" => $record->android,
                "verrelease" => $record->verrelease,
                "sdk" => $record->sdk,
                "productmodel" => $record->productmodel,
                "productbrand" => $record->productbrand,
                "productname" => $record->productname,
                "productmanufacturer" => $record->productmanufacturer,
                "action" => $action,

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
        $data = new DeviceInfo();
        $data['id'] = $request->id;
        $data['android'] = $request->android;
        $data['verrelease'] = $request->verrelease;
        $data['buildid'] = $request->buildid;
        $data['displayid'] = $request->displayid;
        $data['incremental'] = $request->incremental;
        $data['sdk'] = $request->sdk;
        $data['builddate'] = $request->builddate;
        $data['builddateutc'] = $request->builddateutc;
        $data['productmodel'] = $request->productmodel;
        $data['productbrand'] = $request->productbrand;
        $data['productname'] = $request->productname;
        $data['productdevice'] = $request->productdevice;
        $data['productboard'] = $request->productboard;
        $data['productmanufacturer'] = $request->productmanufacturer;
        $data['description'] = $request->description;
        $data['fingerprint'] = $request->fingerprint;
        $data['characteristics'] = $request->characteristics;
        $data['datagoc'] = $request->datagoc;
        $data['note'] = $request->note;
        $data['time'] = time();
        $data['status'] = $request->status;
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',
        ]);

    }

    public function edit($id)
    {
        $data = DeviceInfo::where('id',$id)->first();
        return response()->json($data);
    }


    public function update(Request $request)
    {
        $id = $request->id;
        $data = DeviceInfo::where('id',$id)->first();
        $data->android = $request->android;
        $data->verrelease = $request->verrelease;
        $data->buildid = $request->buildid;
        $data->displayid = $request->displayid;
        $data->incremental = $request->incremental;
        $data->sdk = $request->sdk;
        $data->builddate = $request->builddate;
        $data->builddateutc = $request->builddateutc;
        $data->productmodel = $request->productmodel;
        $data->productbrand = $request->productbrand;
        $data->productname = $request->productname;
        $data->productdevice = $request->productdevice;
        $data->productboard = $request->productboard;
        $data->productmanufacturer = $request->productmanufacturer;
        $data->description = $request->description;
        $data->fingerprint = $request->fingerprint;
        $data->characteristics = $request->characteristics;
        $data->datagoc = $request->datagoc;
        $data->note = $request->note;
        $data->time = time();
        $data->status = $request->status;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    public function delete($id)
    {
        DeviceInfo::where('id',$id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
