<?php

namespace App\Http\Controllers;


use App\Models\Ga_dev;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class Ga_devController extends Controller
{
    public function index()
    {
        $header = [
            'title' => 'Quản lý GaDev',

            'button' => [
                'Create'            => ['id'=>'createNewGadev','style'=>'primary'],
            ]

        ];
        return view('gadev.index')->with(compact('header'));

    }

    public function getIndex(Request $request){
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
        $totalRecords = Ga_dev::select('count(*) as allcount') ->count();
        $totalRecordswithFilter = Ga_dev::select('count(*) as allcount')
            ->Where('gmail','like', '%' .$searchValue. '%')
            ->orWhere('mailrecovery','like', '%' .$searchValue. '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = Ga_dev::orderBy($columnName, $columnSortOrder)
            ->Where('gmail','like', '%' .$searchValue. '%')
            ->orWhere('mailrecovery','like', '%' .$searchValue. '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach ($records as $record) {
//            $btn = ' <a href="javascript:void(0)" onclick="editGadev('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)"  data-id="'.$record->id.'"  class="btn btn-warning editGadev"><i class="ti-pencil-alt"></i></a>';
            $btn = $btn.' <a href="javascript:void(0)"  data-id="'.$record->id.'"  class="btn btn-danger deleteGadev"><i class="ti-trash"></i></a>';




            $data_arr[] = array(
                "id" => $record->id,
                "gmail" => $record->gmail,
                "pass" => $record->pass,
                "mailrecovery" => $record->mailrecovery,
                "vpn_iplogin" => $record->vpn_iplogin,
                "backupcode" => '<div class="truncate">'. $record->backupcode.'</div>',
                "note" => '<div class="truncate">'. $record->note.'</div>',
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        $rules = [
            'gmail' =>'unique:gmail_gadev,gmail'
        ];
        $message = [
            'gmail.unique'=>'Gmail đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Ga_dev();
        $data['gmail'] = strtolower($request->gmail);
        $data['mailrecovery'] = strtolower($request->mailrecovery);
        $data['pass'] = $request->pass;
        $data['vpn_iplogin'] = $request->vpn_iplogin;
        $data['note'] = $request->note;
        $data['backupcode'] = $request->backupcode;
        $data->save();
        $allGadev = Ga_dev::latest('id')->get();
        return response()->json([
            'success'=>'Thêm mới thành công',
            'allGa_dev' => $allGadev
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gadev = Ga_dev::find($id);
        return response()->json($gadev);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)

    {
        $id = $request->gadev_id;
        $rules = [
            'gmail' =>'unique:gmail_gadev,gmail,'.$id.',id',
        ];
        $message = [
            'gmail.unique'=>'Tên Gmail đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = Ga_dev::find($id);
        $data->gmail = strtolower($request->gmail);
        $data->mailrecovery = strtolower($request->mailrecovery);
        $data->vpn_iplogin= $request->vpn_iplogin;
        $data->pass = $request->pass;
        $data->note= $request->note;
        $data->backupcode= $request->backupcode;
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Ga_dev::find($id)->delete();

        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
//        $this->AuthLogin();
        return parent::callAction($method, array_values($parameters));
    }
}
