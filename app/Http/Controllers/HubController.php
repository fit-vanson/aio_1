<?php

namespace App\Http\Controllers;

use App\Models\cocsim;
use App\Models\Hub;
use App\Models\khosim;
use App\Models\sms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HubController extends Controller
{
    public function index(Request $request)
    {
        $cocsim = cocsim::withCount('khosim')->having('khosim_count', 15)->get();
        $hub = Hub::all();
        if ($request->ajax()) {
            $data = Hub::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editHub('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
//                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteHub"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('cocsim', function($data){
                    $cocsim = DB::table('ngocphandang_hubinfo')
                        ->join('ngocphandang_cocsim','ngocphandang_cocsim.id','=','ngocphandang_hubinfo.cocsim')
                        ->where('ngocphandang_cocsim.id',$data->cocsim)
                        ->first();
                    if($cocsim != null){
                        return $cocsim->cocsim;
                    }
                })
                ->editColumn('lockauto', function($data){
                    if($data->lockauto == 0 ){
                        return '<input type="checkbox" class="item_checkbox" checked onchange="checkbox('.$data->id.')">';
                    }return '<input type="checkbox" class="item_checkbox"   onchange="checkbox('.$data->id.')">';

                })

                ->editColumn('timeupdate', function($data) {
                    if($data->timeupdate == 0 ){
                        return  null;
                    }
                    return date( 'd/m/Y - H:i:s ',$data->timeupdate);
                })
                ->rawColumns(['action','lockauto'])
                ->make(true);
        }


        return view('hub.index',compact(['hub','cocsim']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        dd(1);
        $rules = [
            'hubname' =>'unique:ngocphandang_hubinfo,hubname',
            'cocsim' =>'unique:ngocphandang_hubinfo,cocsim',

        ];
        $message = [
            'hubname.unique'=>'Hub Name đã tồn tại',
            'cocsim.unique'=>'Cọc sim đã được sử dụng',

        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new Hub();
        $data['hubname'] = $request->hubname;
        $data['cocsim'] = $request->cocsim;
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
        $dev = Hub::find($id);
        return response()->json($dev);
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
            'cocsim' =>'unique:ngocphandang_hubinfo,cocsim,'.$id.',id',
        ];
        $message = [
            'cocsim.unique'=>'Cọc sim đã được sử dụng',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $phoneOfcocsim =  DB::table('ngocphandang_khosim')
            ->Join('ngocphandang_cocsim','ngocphandang_cocsim.id','=','ngocphandang_khosim.cocsim')
            ->where('ngocphandang_khosim.cocsim',$request->cocsim)
            ->get();
//        dd($request->cocsim);

        $count = count($phoneOfcocsim);
        if($count==15){
            $sms = DB::table('ngocphandang_sms')
                ->Join('ngocphandang_hubinfo','ngocphandang_hubinfo.hubname','=','ngocphandang_sms.hubname')
                ->where('ngocphandang_hubinfo.hubname',$request->hubname)
                ->get();

            $cocsim  = cocsim::where('id',$request->cocsim)->first();
            $cocsim = $cocsim->cocsim;
            foreach ($sms  as $s){
                foreach ($phoneOfcocsim as $phone){
                    if($phone->stt < 10){
                        $s->hubid = $s->hubname.'_Slot0'.$phone->stt;
                    }else{
                        $s->hubid = $s->hubname.'_Slot'.$phone->stt;
                    }
                    $s = sms::where('hubid',$s->hubid)->first();
                    $s->cocsim = $cocsim;
                    $s->phone = $phone->phone;
                    $s->code ='';
                    $s->sms = '';
                    $s->busyjob = '';
                    $s->timebusyjob = 0;
                    $s->timecode = 0;
                    $s->strvalid = 0;
                    $s->save();
                }
            }
            $data = Hub::find($id);
            $data->cocsim = $request->cocsim;
            $data->lockauto = 0;
            $data->timeupdate = time();
            $data->save();
            return response()->json(['success'=>'Cập nhật thành công']);
        }else{
            return response()->json(['errors'=> ['Cọc sim không đủ 15 số']]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Hub::find($id)->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }

    public function checkbox($id){


        $data = Hub::find($id);
        if($data->lockauto == 1){
            $data->lockauto = 0;
            $data->save();
            return response()->json(['success'=>$data->hubname.' đã mở']);
        }else{
            $data->lockauto = 1;
            $data->save();
            return response()->json(['errors'=> $data->hubname.' đã khóa']);
        }

    }

    public function checkboxAll($check){
        $data= Hub::all();
        if ($check == 'true'){
            foreach ($data as $item){
                $item = Hub::find($item->id);
                $item->lockauto = 0;
                $item->save();
            }
            return response()->json(['success'=>' Các Hub đã mở']);
        }
        if ($check == 'false'){
            foreach ($data as $item){
                $item = Hub::find($item->id);
                $item->lockauto = 1;
                $item->save();
            }
            return response()->json(['errors'=> ' Các Hub đã khóa']);
        }
    }
}
