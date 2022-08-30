<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyTwoFaceController extends Controller
{
    public function index()
    {
        return view("verify_two_face.index");
    }

    public function verify(Request $request)
    {

        $rules = [
            'code' =>'required|digits:6',

        ];
        $message = [
            'code.required'=>'Không để trống',
            'code.digits'=>'Mã phải có 6 chữ số',
        ];
//
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
        $secretCode = auth()->user()->secret_code;
        if (!$googleAuthenticator->verifyCode($secretCode, $request->get("code"), 0)) {
            return response()->json(['errors'=> ['Mã không hợp lệ']]);
        }
        session(["2fa_verified" => true]);
        return response()->json(['success'=>'Đăng nhập thành công.']);
    }
}
