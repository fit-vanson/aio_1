<?php

namespace App\Http\Controllers;

use App\Models\log;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(){
        $data = Setting::first();
        return view('settings',compact('data'));
    }

    public function update(Request $request)
    {
        $rules = [
            'limit_cron' =>'numeric|max:20|min:1',
        ];
        $message = [
            'limit_cron.max'=>'Max 20',
            'limit_cron.min'=>'Min 1',

        ];
        $error = Validator::make($request->all(),$rules,$message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $data = Setting::first();
        $data->time_cron = $request->time_cron;
        $data->limit_cron = $request->limit_cron;
        $data->save();
        return response()->json([
            'success'=>'Cập nhật thành công',
            'data' => $data
            ]);
    }

    public function clear_logs(){
        $data = log::where('updated_at','<', Carbon::now()->subDays(14))->count();
        if($data> 0){
            log::where('updated_at','<', Carbon::now()->subDays(14))->delete();
            return response()->json(['success'=>'Xóa thành công.']);
        }else{
            return response()->json(['errors'=>'Không có dữ liệu.']);
        }
    }
}
