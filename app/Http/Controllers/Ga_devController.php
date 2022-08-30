<?php

namespace App\Http\Controllers;


use App\Models\Ga_dev;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class Ga_devController extends Controller
{
    public function index(Request $request)
    {
        $ga_dev = Ga_dev::latest('id')->get();
        if ($request->ajax()) {
            $data = Ga_dev::latest('id')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-warning btn-sm editGadev"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteGadev"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('backup_code', function($row){
                    if($row['bk_1'] !== Null){
                        $bk_1 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_1 = "<i style='color:red;' class='ti-close h5'></i>";
                    }

                    if($row['bk_2'] !== Null){
                        $bk_2 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_2 = "<i style='color:red;' class='ti-close h5'></i>";
                    }

                    if($row['bk_3'] !== Null){
                        $bk_3 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_3 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_4'] !== Null){
                        $bk_4 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_4 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_5'] !== Null){
                        $bk_5 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_5 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_6'] !== Null){
                        $bk_6 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_6 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_7'] !== Null){
                        $bk_7 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_7 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_8'] !== Null){
                        $bk_8 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_8 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_9'] !== Null){
                        $bk_9 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_9 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    if($row['bk_10'] !== Null){
                        $bk_10 = "<i style='color:green;' class='ti-check-box h5'></i>";
                    } else {
                        $bk_10 = "<i style='color:red;' class='ti-close h5'></i>";
                    }
                    return $bk_1 .' '. $bk_2.' '. $bk_3.' '. $bk_4.' '. $bk_5.' '. $bk_6.' '. $bk_7.' '. $bk_8.' '. $bk_9.' '. $bk_10 ;
                })
                ->editColumn('note', function($data){
                    if ($data->note !== null){
                        return "<i style='color:green; ' class='ti-check-box h5'></i>";
                    }
                    return "<i style='color:red;' class='ti-close h5'></i>";
                })
                ->rawColumns(['action','backup_code','note'])
                ->make(true);
        }
        return view('gadev.index',compact('ga_dev'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        $rules = [
            'gmail' =>'unique:ngocphandang_gadev,gmail'
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
            'gmail' =>'unique:ngocphandang_gadev,gmail,'.$id.',id',
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
