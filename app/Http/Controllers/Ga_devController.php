<?php

namespace App\Http\Controllers;


use App\Models\Ga_dev;
use App\Models\Markets;
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
                'View'            => ['id'=>'viewGa_dev','style'=>'success'],
            ],
            'badge' => [
                'Đang phát triển' => ['style'=>'primary'],
                'Đóng'            => ['style'=>'badge badge-warning'],
                'Suspend'         => ['style'=>'badge badge-danger'],
                'Chưa xử dụng'    => ['style'=>'badge badge-dark'],
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
                "gmail" => '<div class="copyButton">'.$record->gmail.'</div>',
                "pass" => '<div class="copyButton">'.$record->pass.'</div>',
                "mailrecovery" => '<div class="copyButton">'.$record->mailrecovery.'</div>',
                "vpn_iplogin" => '<div class="copyButton">'.$record->vpn_iplogin.'</div>',
                "backupcode" => '<div class="truncate copyButton">'. $record->backupcode.'</div>',
                "note" => '<div class="truncate copyButton">'. $record->note.'</div>',
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

    public function getIndexV2(Request $request){

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
            ->count();

        // Get records, also we have included search filter as well
        $records = Ga_dev::orderBy($columnName, $columnSortOrder)
            ->with('devs_1','devs_2','ga','ga_1','ga_2')
            ->Where('gmail','like', '%' .$searchValue. '%')
//            ->Where('gmail','levanhung245i@gmail.com')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $markets = Markets::all();
        $data_arr = array();

        foreach ($records as $record) {
            $dev_markets_1 = $record->devs_1;
            $dev_markets_2 = $record->devs_2;
            $dev_1 = $dev_2 = [];
            $arr = array();
            foreach ($markets as $market){
                $arr+= [
                    $market->market_name => '',
                ];
            }



//            $ga = $record->ga ?  $record->ga->ga_name : '' ;
//            $ga_1 = $record->ga_1 ?  $record->ga_1->ga_name : '';
//            $ga_2 = $record->ga_2 ?  $record->ga_2->ga_name : '';

            $ga = [
                $record->ga ?  $record->ga->ga_name : null ,
                $record->ga_1 ?  $record->ga_1->ga_name : null,
                $record->ga_2 ?  $record->ga_2->ga_name : null
            ];

            $arr += array(
                "id" => $record->id,
//                "ga" => $ga.'-'.$ga_1.'-'.$ga_2,
                "ga" => $ga ,
                "gmail" => '<div class="copyButton">'.$record->gmail.'</div>',
            );
            foreach ($dev_markets_1 as $dev_market_1){


                switch ($dev_market_1->status){
                    case 1:
                        $dev_name = ' <span class="badge badge-primary change_status" data-id="'.$dev_market_1->id.'">'.$dev_market_1->dev_name.'</span> ';
                        break;
                    case 2:
                        $dev_name = ' <span class="badge badge-warning change_status" data-id="'.$dev_market_1->id.'">'.$dev_market_1->dev_name.'</span> ';
                        break;
                    case 3:
                        $dev_name = ' <span class="badge badge-danger change_status" data-id="'.$dev_market_1->id.'">'.$dev_market_1->dev_name.'</span> ';
                        break;
                    default:
                        $dev_name = ' <span class="badge badge-dark change_status" data-id="'.$dev_market_1->id.'">'.$dev_market_1->dev_name.'</span> ';
                        break;
                }
                $dev_1 += [
                    $dev_market_1->markets->market_name => $dev_name,

                ];
            }

            foreach ($dev_markets_2 as $dev_market_2){
                switch ($dev_market_2->status){
                    case 1:
                        $dev_2_name = ' <span class="badge badge-primary change_status" data-id="'.$dev_market_2->id.'">'.$dev_market_2->dev_name.'</span> ';
                        break;
                    case 2:
                        $dev_2_name = ' <span class="badge badge-warning change_status" data-id="'.$dev_market_2->id.'">'.$dev_market_2->dev_name.'</span> ';
                        break;
                    case 3:
                        $dev_2_name = ' <span class="badge badge-danger change_status" data-id="'.$dev_market_2->id.'">'.$dev_market_2->dev_name.'</span> ';
                        break;
                    default:
                        $dev_2_name = ' <span class="badge badge-dark change_status" data-id="'.$dev_market_2->id.'">'.$dev_market_2->dev_name.'</span> ';
                        break;
                }
                $dev_2 += [
                    $dev_market_2->markets->market_name => $dev_2_name,

                ];
            }

            $data = array_merge_recursive($arr,$dev_1, $dev_2);
            $data_arr[] = $data;
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
