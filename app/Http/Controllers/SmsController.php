<?php

namespace App\Http\Controllers;

use App\Models\cocsim;
use App\Models\Hub;
use App\Models\khosim;
use App\Models\sms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{
    public function index(Request $request)
    {


        $cocsim = cocsim::latest('id')->get();
        $khosim= khosim::latest('id')->get();
        $sms = sms::latest('hubid')->get();
        $hubs = Hub::with('cocsims')->get();
        if ($request->ajax()) {
            $data = sms::latest('hubid')->get();
            return Datatables::of($data)
                ->addIndexColumn()
//                ->addColumn('action', function($row){
//                    $btn = ' <a href="javascript:void(0)" onclick="editSms('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteSms"><i class="ti-trash"></i></a>';
//                    return $btn;
//                })
                ->editColumn('timecode', function($data) {
                    if($data->timecode == 0 ){
                        return  null;
                    }
                    return date( 'd/m/Y - H:i:s ',$data->timecode);
                })

//                ->rawColumns(['action'])
                ->make(true);
        }
        return view('sms.index',compact(['sms','khosim','cocsim','hubs']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {

        $rules = [
            'hubid' =>'unique:ngocphandang_sms,hubid',
        ];
        $message = [
            'hubid.unique'=>'Hub ID đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = new sms();
        $data['phone'] = $request->phone;
        $data['cocsim'] = $request->cocsim;
        $data['stt'] = $request->stt;
        $data['time'] = 0;
        $data->save();
        return response()->json(['success'=>'Thêm mới thành công']);
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
    public function show()
    {

    }
    public function showHub( Request $request)
    {
        $hubs= Hub::where('id',$request->id)->first();
        $items = sms::where('hubname', $hubs->hubname)->get();
        return view('sms.showHub',compact(['hubs','items']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sms = sms::find($id);
        return response()->json($sms);
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
        $id = $request->id;
        $rules = [
            'phone' =>'numeric|unique:ngocphandang_khosim,phone,'.$id.',id',
            'stt' =>'numeric|min:1|max:15'
        ];
        $message = [
            'phone.unique'=>'Phone đã tồn tại',
            'phone.numeric'=>'Phone là số',
            'stt.numeric'=>'STT là số',
            'stt.min'=>'STT tối thiệu là 1',
            'stt.max'=>'STT tối đa là 15',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = khosim::find($id);
        $data->phone = $request->phone;
        $data->cocsim = $request->cocsim;
        $data->stt = $request->stt;
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
        khosim::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function resetSMS(){
        DB::table('ngocphandang_sms')->update([
            'cocsim'=>null,
            'phone'=>null,
            'code'=>null,
            'sms'=>null,
            'timecode'=>0,
        ]);

    }
}
