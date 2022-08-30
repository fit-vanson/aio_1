<?php

namespace App\Http\Controllers;


use App\Models\MailManage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPGangsta_GoogleAuthenticator;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FA\Support\Base32;
use Yajra\DataTables\Facades\DataTables;

class MailManageController extends Controller
{
    public function index(Request $request)
    {
        $mail = MailManage::latest()->get();
        if ($request->ajax()) {
            $data = MailManage::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = ' <a href="javascript:void(0)" onclick="editMail('.$row->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger deleteMail"><i class="ti-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('secret_code', function($row){
                    $googleAuthenticator =  new Google2FA();
                    $secretCode = $row->secret_code;

                    if($secretCode == null){
                        return '<span class="badge badge-danger">Chưa kích hoạt 2FA</span>';
                    }else{
                        return $googleAuthenticator->getCurrentOtp($secretCode);
                    }
                })
                ->editColumn('updated_at', function($data) {
                    if($data->updated_at == 0 ){
                        return  null;
                    }
                    return date( 'd/m/Y - H:i:s ',$data->updated_at);
                })
                ->rawColumns(['action','secret_code'])
                ->make(true);
        }
        return view('mailmanage.index',compact('mail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        $rules = [
            'email' =>'unique:ngocphandang_mailmanage,email',
        ];
        $message = [
            'email.unique'=>'Email đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $secret_code = strtoupper(str_replace(' ','',$request->secret_code));

        $isMatch = preg_match('#^([A-Z2-7=]{8})+$#', $secret_code);
        if($isMatch == 0) {
            return response()->json(['errors'=> ['Mã không hợp lệ']]);
        }
        $data = new MailManage();
        $data['email'] = strtolower($request->email);
        $data['secret_code'] = $secret_code;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data->save();
        return response()->json([
            'success'=>'Thêm mới thành công',

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
        $gadev = MailManage::find($id);
        $googleAuthenticator =  new Google2FA();
        $secretCode = $gadev->secret_code;
        if($secretCode == null){
            return '<span class="badge badge-danger">Chưa kích hoạt 2FA</span>';
        }else{
            return $googleAuthenticator->getCurrentOtp($secretCode);
        }
        return response()->json($gadev);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gadev = MailManage::find($id);
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
        $id = $request->id;
        $rules = [
            'email' =>'unique:ngocphandang_mailmanage,email,'.$id.',id',
        ];
        $message = [
            'email.unique'=>'Tên Gmail đã tồn tại',
        ];

        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $secret_code = strtoupper(str_replace(' ','',$request->secret_code));

        $isMatch = preg_match('#^([A-Z2-7=]{8})+$#', $secret_code);
        if($isMatch == 0) {
            return response()->json(['errors'=> ['Mã không hợp lệ']]);
        }
        $data = MailManage::find($id);
        $data->email = strtolower($request->email);

        $data->secret_code= $secret_code;
        $data->updated_at= time();
        $data->save();
        return response()->json(['success'=>'Cập nhật thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)    {
        MailManage::find($id)->delete();

        return response()->json(['success'=>'Xóa thành công.']);
    }

    public function callAction($method, $parameters)
    {
//        $this->AuthLogin();
        return parent::callAction($method, array_values($parameters));
    }

}
