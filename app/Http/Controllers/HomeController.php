<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function getHome(){

        $googleAuthenticator = new \PHPGangsta_GoogleAuthenticator();
        // Tạo secret code
        $secretCode = $googleAuthenticator->createSecret();
        // Tạo QR code từ secret code. Tham số đầu tiên là tên. Chúng ta sẽ hiển thị
        // email hiện tại của người dùng. Tham số tiếp theo là secret code và tham số cuối cùng
        // là tiêu đề của ứng dụng. Sử dụng để người dùng biết code này đang sử dụng cho dịch vụ nào
        // Bạn có thể tùy ý sử dụng tham số 1 và 3.
        $qrCodeUrl = $googleAuthenticator->getQRCodeGoogleUrl(
            auth()->user()->email, $secretCode, config("app.name")
        );
        // Lưu secret code vào session để phục vụ cho việc kiểm tra bên dưới
        // và update vào database trong trường hợp người dùng nhập đúng mã được sinh ra bởi
        // ứng dụng Google Authenticator
        $project = ProjectModel::count();
        $projectLastMonth = ProjectModel::select('*')
            ->whereBetween('created_at',
                [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]
            )
            ->count();
        $projectInMonth = ProjectModel::select('*')
            ->whereBetween('created_at',
                [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]
            )
            ->count();

        $data = ProjectModel::select(
            'Chplay_status','Amazon_status','Samsung_status','Xiaomi_status','Oppo_status','Vivo_status','Huawei_status',
            'Chplay_package','Amazon_package','Samsung_package','Xiaomi_package','Oppo_package','Vivo_package','Huawei_package'
        )
            ->get()
            ->toArray();
        $Chplay_status = array_count_values(array_filter(array_column($data, 'Chplay_status')));
        $Amazon_status = array_count_values(array_filter(array_column($data, 'Amazon_status')));
        $Samsung_status = array_count_values(array_filter(array_column($data, 'Samsung_status')));
        $Xiaomi_status = array_count_values(array_filter(array_column($data, 'Xiaomi_status')));
        $Oppo_status = array_count_values(array_filter(array_column($data, 'Oppo_status')));
        $Vivo_status = array_count_values(array_filter(array_column($data, 'Vivo_status')));
        $Huawei_status = array_count_values(array_filter(array_column($data, 'Huawei_status')));

        session(["secret_code" => $secretCode]);
        return view("index", compact(
            "qrCodeUrl",
            "project",
            "projectLastMonth",
            "projectInMonth",
            "Chplay_status",
            "Amazon_status",
            "Samsung_status",
            "Xiaomi_status",
            "Oppo_status",
            "Vivo_status",
            "Huawei_status"
        ));

    }
    public function getLogin(){
        return view('login');
    }

    public function postLogin(Request $request){
        $validate = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        $name = $request->username;
        $password = $request->password;
        $remember = $request->input('remember_me');
        if(Auth::attempt(['name'=>$name,'password'=>$password],$remember)){
            return \redirect()->intended('admin/');
        }else{
            return back()->withInput()->with('error','Mật khẩu hoặc tài khoản không đúng!');
        }

    }
    public function logout(){
        session()->flush();
        Auth::logout();
        return \redirect()->intended('login');
    }

    public function fake(){
        return view('fake_dashboard');
    }

//    public static  function Market($market){
//        $data_arr = array();
//
//        $data = ProjectModel::select(
//            'Chplay_status','Amazon_status','Samsung_status','Xiaomi_status','Oppo_status','Vivo_status','Huawei_status',
//            'Chplay_package','Amazon_package','Samsung_package','Xiaomi_package','Oppo_package','Vivo_package','Huawei_package'
//        )
//            ->get()
//            ->toArray();
//
//
//         $status = array_count_values(array_filter(array_column($data, 'Chplay_status')));
//         dd(array_sum($status));
//
//        $counts = array_count_values($a);
//         dd($counts);
//        foreach ($data as $item){
//            $data_arr [] = [
//                'status' => $item->Chplay_status
//            ];
//        }
////        dd($data);
//
//
//        if($markert == 'Chplay'){
//            $data = ProjectModel::select('Chplay_status')->where('Chplay_package','<>',NULL)->where('Chplay_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Chplay_status
//                ];
//            }
//        }elseif ($markert == 'Amazon'){
//            $data = ProjectModel::select('Amazon_status')->where('Amazon_package','<>',NULL)->where('Amazon_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Amazon_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Amazon_status',$status)->where('Amazon_package','<>',NULL)->count() :  ProjectModel::where('Amazon_package','<>',NULL)->count();
//        }elseif ($markert == 'Samsung') {
//            $data = ProjectModel::select('Samsung_status')->where('Samsung_package','<>',NULL)->where('Samsung_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Amazon_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Samsung_status', $status)->where('Samsung_package', '<>', NULL)->count() : ProjectModel::where('Samsung_package', '<>', NULL)->count();
//        }elseif ($markert == 'Xiaomi') {
//            $data = ProjectModel::select('Xiaomi_status')->where('Xiaomi_package','<>',NULL)->where('Xiaomi_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Xiaomi_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Xiaomi_status', $status)->where('Xiaomi_package', '<>', NULL)->count() : ProjectModel::where('Xiaomi_package', '<>', NULL)->count();
//        }elseif ($markert == 'Oppo') {
//            $data = ProjectModel::select('Oppo_status')->where('Oppo_package','<>',NULL)->where('Oppo_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Oppo_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Oppo_status', $status)->where('Oppo_package', '<>', NULL)->count() : ProjectModel::where('Oppo_package', '<>', NULL)->count();
//        }elseif ($markert == 'Vivo') {
//            $data = ProjectModel::select('Vivo_status')->where('Vivo_package','<>',NULL)->where('Vivo_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Vivo_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Vivo_status', $status)->where('Vivo_package', '<>', NULL)->count() : ProjectModel::where('Vivo_package', '<>', NULL)->count();
//        }elseif ($markert == 'Huawei') {
//            $data = ProjectModel::select('Huawei_status')->where('Huawei_package','<>',NULL)->where('Huawei_status','<>',NULL)->get();
//            foreach ($data as $item){
//                $data_arr [] = [
//                    'status' => $item->Huawei_status
//                ];
//            }
////            $data = $status ? ProjectModel::where('Huawei_status', $status)->where('Huawei_package', '<>', NULL)->count() : ProjectModel::where('Huawei_package', '<>', NULL)->count();
//        }
//
//
////        if($status){
////            if (in_array($status, array_column($data_arr, 'status'))) {
////                $counts = array_count_values(array_column($data_arr, 'status'))[$status];
////            }else{
////                $counts = 0;
////            }
////        }else {
////            $counts = count($data_arr);
////        }
//        return $data_arr;
//    }
//    public static function statusMarket($market,$status= Null){
//        $data_arr = self::Market($market);
//        dd($data_arr);
////        if($status){
////            if (in_array($status, array_column($data_arr, 'status'))) {
////                $counts = array_count_values(array_column($data_arr, 'status'))[$status];
////            }else{
////                $counts = 0;
////            }
////        }else {
////            $counts = count($data_arr);
////        }
//        $data = (array_count_values(array_column($data_arr, 'status')));
//
//        return view("index", compact(
//            "data"
//        ));
////        return array_count_values(array_column($data_arr, 'status'));
//    }

}
