<?php

namespace App\Http\Controllers;

use App\Models\cocsim;
use App\Models\khosim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use function PHPUnit\Framework\isNull;

class CocsimController extends Controller
{
    public function index(Request $request)
    {
        $cocsim= cocsim::latest('id')->where('id','<>',1)->get();
        if ($request->ajax()) {
            $data = cocsim::latest('id')->where('id','<>',1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editCocsim('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteCocsim"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('time', function($data) {
                    if($data->time == 0 ){
                        return  null;
                    }
                    return date( 'd/m/Y - H:i:s ',$data->time);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('cocsim.index',compact(['cocsim']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
//        dd($request->all());


        $rules = [
            'cocsim' =>'unique:ngocphandang_cocsim,cocsim',
            'phone.*' =>'unique:ngocphandang_khosim,phone',

        ];
        $message = [
            'cocsim.unique'=>'Tên cọc sim đã tồn tại',
            'phone.*.unique'=>'Số :input đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $data = new cocsim();
        $data['cocsim'] = $request->cocsim;
        $data['note'] = $request->note;
        $data['time'] = time();
        $data->save();
        for ($i = 1; $i <=15 ; $i++) {
            $phone[] = [
                'phone' => $request->phone[$i-1],
                'cocsim' => $data->id,
                'stt' => $i,
            ];
        }
        khosim::insert($phone);

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
        $cocsim = cocsim::with('khosim')->find($id);
        return response()->json($cocsim);
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
//        dd($request->all());


        $id = $request->id;
        $phone_id = $request->phone_id;

        $rules = [
            'cocsim' =>'unique:ngocphandang_cocsim,cocsim,'.$id.',id',
//            'phone.*' =>'unique:ngocphandang_khosim,phone,'.$phone_id.',id',
        ];

        foreach($phone_id as $key => $val)
        {
            $rules['phone.'.$key] = 'unique:ngocphandang_khosim,phone,'.$val.',id';
        }

        $message = [
            'cocsim.unique'=>'Tên cọc sim đã tồn tại',
            'cocsim.required'=>'Tên cọc sim không để trống',
//            'phone.*.numeric' => ' :input không phải là số',
            'phone.*.unique'=>'Số :input đã tồn tại',

        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }


        $data = cocsim::find($id);
        $data->cocsim = $request->cocsim;
        $data->note = $request->note;
        $phoneOfCocsim = khosim::where('cocsim', $id)->get()->toArray();
        for($i =0; $i < 15; $i++)
        {
            $idP = $phoneOfCocsim[$i];
            $phone = khosim::where('id',$idP)->first();
            $phone->phone = $request->phone[$i];
            $phone->stt = $i+1;
            $phone->save();
        }
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
        $cocsim = cocsim::find($id);
//        $cocsim->khosim()->detach();
        $cocsim->delete();
        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, array_values($parameters));
    }
}
