<?php

namespace App\Http\Controllers;

use App\Models\Dev;
use App\Models\Dev_Huawei;
use App\Models\Dev_Vivo;
use App\Models\Market_dev;
use App\Models\MarketProject;
use App\Models\ProjectModel;
use App\Models\Setting;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Mavinoo\Batch\Batch;
use Nelexa\GPlay\GPlayApps;
use Telegram\Bot\Laravel\Facades\Telegram;
use ZipArchive;

class CronProjectController extends Controller

{
    private  $domainHuawei = 'https://connect-api.cloud.huawei.com';

    // Vivo
    private $SIGN_METHOD_HMAC = "hmac-sha256";
    private $domainVivo = 'https://developer-api.vivo.com/router/rest';
//    private $access_key =  '0987226aea07435e9b8a0fabcd38e7cd';
//    private $accessSecret = '1bcc877c4e6a41a18a4ecc1419d07cbc';

    public function __construct()
    {
        ini_set('max_execution_time', 600);
        Artisan::call('optimize:clear');
    }

    public function index(){
        ini_set('max_execution_time', 600);
        Artisan::call('optimize:clear');
        $chplay = $huawei = $vivo = '';
        try {
            $chplay = $this->Chplay(3);
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project CHplay : ' . $exception->getLine());
        }
        try {
            $huawei = $this->Huawei(3);
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project Huawei : ' . $exception->getLine());
        }
        try {
            $vivo   = $this->Vivo(3);
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project Vivo : ' . $exception->getLine());
        }


        try {
            $samsung   = $this->samsung();
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project Samsung : ' . $exception->getLine());
        }




        if($chplay !== false   || $huawei !== false || $vivo !== false  ){
            echo '<META http-equiv="refresh" content="5;URL=' . url("cronProject") . '">';
        }
    }

    public function Chplay($status_upload = null){
        $gplay = new \Nelexa\GPlay\GPlayApps();
        if(isset(\request()->package)){
            $appsChplay = MarketProject::where('package',\request()->package)
                ->where('market_id',1)
                ->get();
        }elseif(isset(\request()->projectID)){
            $appsChplay = MarketProject::where('id',\request()->projectID)
                ->where('market_id',1)
                ->get();
        }
        else {
            $time = Setting::first();
            $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
            $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;
            $appsChplay = MarketProject::where('market_id', 1)
                ->where('status_upload','like','%'. $status_upload.'%')
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
                ->paginate($time->limit_cron);
        }

        if($appsChplay){
            $ch =  '';
            $sms = '';
            foreach ($appsChplay as $appChplay){
                $package = $appChplay->package;
                $existApp =  $gplay->existsApp($package);

                if($existApp){
                    try {
                        $appInfo = $gplay->getAppInfo($package);
                        $data = [
                            'released' => $appInfo->getReleased()->getTimestamp(),
                            'updated' => $appInfo->getUpdated()->getTimestamp(),
                        ];
                        $appChplay->status_app = 1;
                        $appChplay->policy_link = $appInfo->getPrivacyPoliceUrl();
                        $appChplay->app_link = $appInfo->getUrl();
                        $appChplay->app_link = $appInfo->getUrl();

                        $appChplay->bot_installs = $appInfo->getInstalls();
                        $appChplay->bot_numberVoters = $appInfo->getNumberVoters();
                        $appChplay->bot_numberReviews = $appInfo->getNumberReviews();
                        $appChplay->bot_score = $appInfo->getScore();
                        $appChplay->bot_appVersion = $appInfo->getAppVersion();
                        $appChplay->bot = $data;
                        $appChplay->bot_time = time();
                        $appChplay->save();
                    }catch (\Exception $exception) {
                        Log::error('Message:' . $exception->getMessage() . '--- google appInfo : '.$appChplay->id.'---' . $exception->getLine());
                    }

                }else{
                    $appChplay->status_app = 6;
                    $appChplay->bot_time = time();
                    $appChplay->save();
                    $sms .= "\n<b>Project name: </b>"
                        . '<code>'.$appChplay->project->projectname.'</code> - '
                        . "<code>Check</code>";
                }
                $ch .= '<br/>'.'Dang chay:  '.  '-'. $appChplay->project->projectname .'--'.$appChplay->status_app.' - '. Carbon::now('Asia/Ho_Chi_Minh');

            }
            $this->sendMessTelegram('Chplay',$sms);

            if(\request()->return){
                return response()->json($appChplay);
            }else{

                echo '<br/>' .'=========== Chplay ==============' ;
                echo '<br/><b>'.'Yêu cầu:';
                echo '<br/>&emsp;'.'- Project có package Chplay'.'</b><br/><br/>';
                echo $ch;
            }
        }
        if(count($appsChplay)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }

    }

    public function getPackage(Request $request)
    {
        if ($request->id){
            $pattern = "/^C+[0-9].*/i";
            $regex = preg_match($pattern,$request->id);
            if($regex != 1){
                $package = $request->id;
                $appInfo = new GPlayApps();
                $existApp =  $appInfo->existsApp($package);
                if($existApp){
                    $appInfo = $appInfo->getAppInfo($package);
                    echo $appInfo->getIcon();
                    echo ' | '. $appInfo->getName();
                    echo ' | '. $appVersion = ($appInfo->getAppVersion() != null)  ? $appInfo->getAppVersion() : 'null';
                    echo ' | '. $score = ($appInfo->getScore() != null)  ? number_format($appInfo->getScore(),1) : 'null';
                    echo ' | '. $install =  ($appInfo->getInstalls() != null)  ? number_format($appInfo->getInstalls()) : 'null';
                    return;
                }else{
                    return 'null';
                }
            }else{
                $ch = curl_init("https://web-dra.hispace.dbankcloud.cn/uowap/index?method=internal.getTabDetail&uri=app|".$request->id); // such as http://example.com/example.xml
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $data = curl_exec($ch);
                $data = json_decode($data,true);
                if(count($data['layoutData'])>0)
                {
                    echo '<a href="https://appgallery.cloud.huawei.com/appdl/'.$request->id.'"> Download: '.$data['layoutData'][0]['dataList'][0]['name'].'</a>';
                    return;
                }else{
                    return 'null';
                }
                curl_close($ch);
                return;
            }
        }
        return '<br><b> CHplay </b>: '.URL::current().'?id=com.xxx.xxx'.'<br><br> <b>Huawei</b> : '.URL::current().'?id=Cxxx';
    }

    function array_search_id($search_value, $array, $id_path) {

        if(is_array($array) && count($array) > 0) {

            foreach($array as $key => $value) {

                $temp_path = $id_path;

                // Adding current key to search path
                array_push($temp_path, $key);

                // Check if this value is an array
                // with atleast one element
                if(is_array($value) && count($value) > 0) {
                    $res_path = $this->array_search_id(
                        $search_value, $value, $temp_path);

                    if ($res_path != null) {
                        return $res_path;
                    }
                }
                else if($value == $search_value) {
                    return join(" --> ", $temp_path);
                }
            }
        }

        return null;
    }







    //==========================================================================

    public function Huawei($status_upload = null){
        if(isset(\request()->projectID)){
            $appsHuawei = MarketProject::where('id',\request()->projectID)->get();
        }else {
            $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;
            $time =  Setting::first();
            $timeCron = Carbon::now()
                ->setTimezone('Asia/Ho_Chi_Minh')
                ->subMinutes($time->time_cron)
                ->timestamp;

            $appsHuawei = MarketProject::where('market_id', 7)
                ->where('status_upload', 'like', '%' . $status_upload . '%')
                ->where(function ($query) {
                    $query->where('dev_id', '<>', 0)
                        ->Where('dev_id', '<>', null);
                })
                ->whereHas('dev', function ($query) {
                    return $query
                        ->whereNotNull('api_client_id')
                        ->where('api_client_id', '<>', '');
                })
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
                ->paginate($time->limit_cron);
        }
        if(count($appsHuawei)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }
        if($appsHuawei){
            $string = '';
            $sms =  '';
            $status_cron =  'Mặc định';
            $status_app = 6;
            foreach ($appsHuawei as $appHuawei){
                $string .=  '<br/>'.'Dang chay:  '. '-'.$appHuawei->project->projectname.' - ';
                try {
                    if(!$appHuawei->dev){
                        $appHuawei->status_app = $status_app;
                        $status_cron = 'check';
                        $appHuawei->bot_time = time();
                        $appHuawei->save();
                        return  response()->json(['error'=>'Chưa có DEV', 'project'=>$appHuawei,'status'=>$status_cron]);

                    }else{
                        if(!$appHuawei->dev->api_token){
                            $appHuawei->status_app = $status_app;
                            $status_cron = 'check';
                            $appHuawei->bot_time = time();
                            $appHuawei->save();
                            return  response()->json(['error'=>'DEV chưa có Token', 'project'=>$appHuawei,'status'=>$status_cron]);
                        }else{
                            $appHuawei = $this->update_token_huawei($appHuawei);
                            $appID =  $appHuawei->appID ? $appHuawei->appID  :  $this->AppInfoPackageHuawei($appHuawei);
                            if($appID){
                                $appInfo    = $this->AppInfoHuawei($appHuawei);

                                if($appInfo){
                                    $reportApp  = $this->reportAppHuawei($appHuawei);
                                    $scoreApp  = $this->getScoreHuawei($appHuawei);
                                    if ($appHuawei->bot){
                                        $dataBot = json_decode($appHuawei->bot,true);
                                        $data = $dataBot + $reportApp ;
                                    }else{
                                        $data = $reportApp;
                                    }
                                    $status = $appInfo['appInfo']['releaseState'];
                                    switch ($status){
                                        case 0:
                                            $status_app = 1;
                                            $status_cron = 'released';
                                            break;
                                        case 1 :
                                            $status_app = 4;
                                            $status_cron = 'Release rejected';
                                            break;
                                        case  11:
                                            $status_app = 4;
                                            $status_cron = 'Release canceled';
                                            break;
                                        case 2 :
                                            $status_app = 3;
                                            $status_cron = 'Removed (including forcible removal)';
                                            break;
                                        case 6 :
                                            $status_app = 3;
                                            $status_cron = 'Removal requested';
                                            break;
                                        case 8 :
                                            $status_app = 3;
                                            $status_cron = 'Update rejected';
                                            break;
                                        case 9:
                                            $status_app = 3;
                                            $status_cron = 'Release rejected';
                                            break;
                                        case 3:
                                            $status_app = 6;
                                            $status_cron = 'Releasing';
                                            break;
                                        case  4 :
                                            $status_app = 6;
                                            $status_cron = 'Reviewing';
                                            break;
                                        case  5:
                                            $status_app = 6;
                                            $status_cron = 'Updating';
                                            break;
                                        case 7:
                                            $status_app = 0;
                                            $status_cron = 'Draft';
                                            break;
                                        case 10:
                                            $status_app = 2;
                                            $status_cron = 'Removed by developer';
                                            break;
                                    }

                                    $data['status'] = $status;
                                    $data['message'] = $appInfo['auditInfo']['auditOpinion'];
                                    $data['updateTime'] = $appInfo['appInfo']['updateTime'];
                                    krsort($data);
                                    $appHuawei->bot_appVersion =   array_key_exists('versionNumber',$appInfo['appInfo']) ? $appInfo['appInfo']['versionNumber'] : null;
                                    $appHuawei->policy_link =  array_key_exists('privacyPolicy',$appInfo['appInfo']) ? $appInfo['appInfo']['privacyPolicy'] : null;
                                    $appHuawei->appID = $appID ;
                                    $appHuawei->app_link = 'https://appgallery.huawei.com/app/C'.$appID ;
                                    $appHuawei->bot = json_encode($data);
                                    $appHuawei->bot_score = $scoreApp ;
                                    $appHuawei->bot_installs = array_sum(array_column($data, 'Total_downloads'));
                                }
                            }
                            $appHuawei->bot_time = time();
                            $appHuawei->status_app = $status_app;
                            $appHuawei->save();
                            $string .= $status_cron ;
                            if($status_app !=1){
                                $sms .= "\n<b>Project name: </b>"
                                    . '<code>'.$appHuawei->project->projectname.'</code> - '
                                    . '<code>'.$status_cron.'/<code>';
                            }
                        }
                    }
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- cronHuawei: '.$appHuawei->id.'---' . $exception->getLine());
                }
            }
            $this->sendMessTelegram('Huawei',$sms);
            if(\request()->return){
                return response()->json(['success'=>'OK', 'project'=>$appHuawei,'status'=>$status_cron]);
            }else{
                echo '<br/><br/>';
                echo '<br/>' .'=========== Huawei ==============' ;
                echo '<br/><b>'.'Yêu cầu:';
                echo '<br/>&emsp;'.'- Project có package Huawei.';
                echo '<br/>&emsp;'.'- Dev Huawei có Client ID và Client Secret'.'</b>';
                echo $string;
                return ;
            }

        }
    }

    public function getTokenHuawei(){
        $token = '';
        $domain = $this->domainHuawei;
        $endpoint = "/api/oauth2/v1/token";
        $devs_huawei = Market_dev::where('market_id', 7)
            ->whereNotNull('api_client_id')
            ->where('api_client_id','<>','')
//            ->where('api_expires_in_token','>',time())
            ->get();
        if($devs_huawei) {
            foreach ($devs_huawei as $dev) {
                if ($dev->api_expires_in_token < time()) {
                    $clientID = $dev->api_client_id;
                    $clientSecret = $dev->api_client_secret;
                    $dataArr = array(
                        'grant_type' => 'client_credentials',
                        'client_id' => $clientID,
                        'client_secret' => $clientSecret,
                    );
                    try {
                        $response = Http::withHeaders([
                            'Content-Type: application/json',
                        ])->post($domain.$endpoint, $dataArr);
                        if ($response->successful()){
                            $result = $response->json();
                            if (isset($result['access_token'])) {
                                $dev->api_token = $result['access_token'];
                                $dev->api_expires_in_token = $result['expires_in'] + time();
                            } else {
                                $dev->api_client_id = null;
                                Log::error('Message:' . '--- No get token: ' . $dev->id . '--' . $dev->dev_name);
                            }
                            $dev->save();
                        }
                    }catch (\Exception $exception) {
                        Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
                    }
                }
            }
        }
        return true;
    }

    public function update_token_huawei($appHuawei){
        $token = '';
        $domain = $this->domainHuawei;
        $endpoint = "/api/oauth2/v1/token";
        $dev = $appHuawei->dev;
        if ($dev->api_expires_in_token < time()) {
            $clientID = $dev->api_client_id;
            $clientSecret = $dev->api_client_secret;
            $dataArr = array(
                'grant_type' => 'client_credentials',
                'client_id' => $clientID,
                'client_secret' => $clientSecret,
            );
            try {
                $response = Http::withHeaders([
                    'Content-Type: application/json',
                ])->post($domain.$endpoint, $dataArr);
                if ($response->successful()){
                    $result = $response->json();
                    if (isset($result['access_token'])) {
                        $dev->api_token = $result['access_token'];
                        $dev->api_expires_in_token = $result['expires_in'] + time();
                    } else {
                        $dev->api_client_id = null;
                        Log::error('Message:' . '--- No get token: ' . $dev->id . '--' . $dev->dev_name);
                    }
                    $appHuawei->dev->save();
                }
            }catch (\Exception $exception) {
                Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
            }
        }
        return $appHuawei;
    }

    public function setTokenHuawei(){

    }

    public function AppInfoHuawei($appHuawei){
        $data = '';
        $token = $appHuawei->dev->api_token;
        $clientID = $appHuawei->dev->api_client_id;
        $appID = $appHuawei->appID;

        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        $domain = $this->domainHuawei;
        $endpoint = "/api/publish/v2/app-info?appid=".$appID;
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- AppInfo: ' . $exception->getLine());
        }
        return $data;
    }

    public function AppInfoPackageHuawei($appHuawei): string
    {
        $appID = '';
        $token = $appHuawei->dev->api_token;
        $clientID = $appHuawei->dev->api_client_id;
        $package = $appHuawei->package;
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        $domain = $this->domainHuawei;
        $endpoint = "/api/publish/v2/appid-list?packageName=".$package;
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = json_decode(json_encode($response->json()));
                if($data->ret->code == 0){
                    $appID =  $data->appids[0]->value;
//                    $appHuawei->appID =  $appID;
//                    $appHuawei->save();
                }
            }

        }catch (\Exception $exception) {
            Log::error('Message: AppInfoPackageHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $appID;
    }

    public function reportAppHuawei($appHuawei){
        $token = $appHuawei->dev->api_token;
        $clientID = $appHuawei->dev->api_client_id;
        $appID = $appHuawei->appID;

        if(isset($_GET['submonth'])){
            $startTime  =  Carbon::now()->subMonth($_GET['submonth'])->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->subMonth($_GET['submonth'])->endOfMonth()->format('Ymd');
            $monthCron  =  Carbon::now()->subMonth($_GET['submonth'])->format('Ym');

        }else{
            $startTime  =  Carbon::now()->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->format('Ymd');
            $monthCron  =  Carbon::now()->format('Ym');
        }
        $lang='en-US';
        $domain = $this->domainHuawei;
        $endpoint = '/api/report/distribution-operation-quality/v1/appDownloadExport/'.$appID.'?language='.$lang.'&startTime='.$startTime.'&endTime='.$endTime.'&groupBy=businessType';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        $result = [];
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
                if($data['ret']['code'] == 0){
                    $file = $this->readCSV($data['fileURL'],array('delimiter' => ','));
                    if($file){
                        $result[$monthCron] =[
                            'Impressions' => $file['Impressions'],
                            'Details_page_views' => $file['Details page views'],
                            'Total_downloads' => $file['Total downloads'] ,
                            'Uninstalls' => $file['Uninstalls (installed from AppGallery)'],
                        ];
                    }
                }
            }
        }catch (\Exception $exception) {
            Log::error('Message: reportAppHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $result;
    }

    public function getReviewsHuawei($domain,$token,$clientID,$appID){
        $data = '';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        $countries = 'US';
        $lang='US';
        $beginTime = Carbon::now()->subDays(7)->valueOf();
        $endTime = Carbon::now()->valueOf();
        $endpoint = '/api/reviews/v1/manage/dev/reviews?appid='.$appID.'&countries='.$countries.'&language='.$lang.'&beginTime='.$beginTime.'&endTime='.$endTime;
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- getReviews: ' . $exception->getLine());
        }
        return $data;
    }

    public function getScoreHuawei($appHuawei){
        $result = '';
        $token = $appHuawei->dev->api_token;
        $clientID = $appHuawei->dev->api_client_id;
        $appID = $appHuawei->appID;
        if(isset($_GET['submonth'])){
            $startTime  =  Carbon::now()->subMonth($_GET['submonth'])->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->subMonth($_GET['submonth'])->endOfMonth()->format('Ymd');

        }else{
            $startTime  =  Carbon::now()->startOfMonth()->timestamp;
            $endTime    =  Carbon::now()->timestamp;
        }
        $lang='US';
        $domain = $this->domainHuawei;
        $endpoint = '/api/reviews/v1/manage/dev/ratings?appId='.$appID.'&countries='.$lang.'&beginTime='.$startTime.'&endTime='.$endTime;
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
                if ($data['ret']['rtnCode'] == 0){
                    $result = $data['data']['score']['averageScore'];
                }else{
                    $result = 0;
                    Log::info('Message: reportAppHuawei:' . $appHuawei->project->projectname . '--- Code:' . $data['ret']['rtnCode'].': '.$data['ret']['rtnDesc']);
                }
            }
        }catch (\Exception $exception) {
            Log::error('Message: reportAppHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $result;
    }

    public function getUploadUrl($domain,$token,$clientID,$appID,$suffix='apk',$releaseType=1){
        $data = '';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
//            'Content-Type'=>'application/json',
        ];
        $endpoint = "/api/publish/v2/upload-url?appId=$appID&suffix=$suffix&releaseType=$releaseType";

        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
            }

        }catch (\Exception $exception) {
            Log::error('Message: AppInfoPackageHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $data;
    }

    public function readCSV($csvFile, $array){
        try {
            $file_handle = fopen($csvFile, 'r');
            while (!feof($file_handle)) {
                $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
            }
            fclose($file_handle);
            $header = array_shift($line_of_text);
            $rows = array_filter($line_of_text);
            $data = [];
            foreach ($rows as $row){
                $data = array_combine($header, $row) ;
            }
        }catch (\Exception $exception) {
            Log::error('Message: readCSV:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $data;
    }

    //==========================================================================

    public function Vivo($status_upload = null){

        if(isset(\request()->projectID)){
            $appsVivo = MarketProject::where('id',\request()->projectID)->get();
        }else {
            $time =  Setting::first();
            $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
            $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;

            $appsVivo = MarketProject::where('market_id', 6)
                ->where('status_upload','like','%'. $status_upload.'%')
                ->whereHas('dev', function ($query) {
                    return $query
                        ->whereNotNull('api_access_key')
                        ->where('api_access_key','<>','');
                })
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
                ->paginate($time->limit_cron);
        }

        if(count($appsVivo)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }

        if($appsVivo){
            $sms = $string = '';
            $status_cron =  'Mặc định';
            $status_app =  6;
            foreach ($appsVivo as $appVivo){
                $string .=  '<br/>'.'Dang chay: '. '-'.$appVivo->project->projectname.'-';
                try{
                    if(!$appVivo->dev){
                        $appVivo->status_app = $status_app;
                        $status_cron = 'check';
                        $appVivo->bot_time = time();
                        $appVivo->save();
                        return  response()->json(['error'=>'Chưa có DEV', 'project'=>$appVivo,'status'=>$status_cron]);
                    }else {
                        if(!$appVivo->dev->api_access_key || !$appVivo->dev->api_client_secret ){
                            $appVivo->status_app = $status_app;
                            $status_cron = 'check';
                            $appVivo->bot_time = time();
                            $appVivo->save();
                            return  response()->json(['error'=>'DEV chưa có api_access_key', 'project'=>$appVivo,'status'=>$status_cron]);
                        }else{
                            $get_Vivo = $this->get_Vivo($appVivo->dev->api_access_key, $appVivo->dev->api_client_secret, $appVivo->package);
                            if ($get_Vivo->data) {
                                $status = $get_Vivo->data->onlineStatus;
                                switch ($status) {
                                    case 0:
                                        $status_app = 2;
                                        $status_cron = 'Unpublished';
                                        break;
                                    case 1  :
                                        $status_app = 1;
                                        $status_cron = 'Published';
                                        break;
                                    case 3 :
                                        $status_app = 1;
                                        $status_cron = 'To be published';
                                        break;
                                    case 2:
                                        $status_app = 3;
                                        $status_cron = 'Removed';
                                        break;
                                }
                                $dataArr = [
                                    'status' => $status
                                ];

                                $appVivo->bot = $dataArr;
                                $appVivo->bot_appVersion = $get_Vivo->data->versionName;
                                $appVivo->policy_link = isset($get_Vivo->data->privacyStatement) ? $get_Vivo->data->privacyStatement : null;
                                $appVivo->status_app = $status_app;
                            }else{
                                $appVivo->status_app = $status_app;
                                $status_cron = $get_Vivo->subMsg;
                            }
                        }
                    }

                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage(). '--- appsVivo: '.$appVivo->id.':--' . $exception->getLine());
                }
                $appVivo->bot_time = time();
                $appVivo->save();
                $string .= $status_cron;
                if($status_app !=1){
                    $sms .= "\n<b>Project name: </b>"
                        . '<code>'.$appVivo->project->projectname.'</code> - '
                        . '<code>'.$status_cron.'</code>';
                }
            }
            $this->sendMessTelegram('Vivo',$sms);
            if(\request()->return){
                return  response()->json(['success'=>'OK', 'project'=>$appVivo,'status'=>$status_cron]);
            }else{
                echo '<br/><br/>';
                echo '<br/>' .'=========== Vivo ==============' ;
                echo '<br/><b>'.'Yêu cầu:';
                echo '<br/>&emsp;'.'- Project có Package của Vivo.';
                echo '<br/>&emsp;'.'- Dev Vivo có Client ID và Client Secret'.'</b><br/><br/>';
                echo $string;
                return ;

            }

        }
    }

    public function get_Vivo($access_key,$accessSecret,$packageName){
        $param = array(
            'target_app_key' => 'developer',
            'method' => 'app.detail',
            'access_key' => $access_key,
            'format' => 'json',
            'sign_method' => 'hmac-sha256',
            'packageName' =>$packageName,
            'timestamp' => Carbon::now()->timestamp,
            'v' => '1.0',
        );
        $param['sign'] = $this->sign_Vivo($param,$accessSecret,$this->SIGN_METHOD_HMAC);
        $data = $this->getUrlParamsFromMap_Vivo($param);
        $curl = curl_init($this->domainVivo);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
            'Accept: application/json'
        ));

        $result = curl_exec($curl);
        $result = (json_decode($result));
        if($result->code == 0){
            return $result;
        }
        return false;

    }

    public function sign_Vivo($paramsMap,$accessSecret,$signMethod){
        $params = $this->getUrlParamsFromMap_Vivo($paramsMap);
        if ($this->SIGN_METHOD_HMAC == $signMethod) {
            return $this->hmacSHA256_Vivo($params, $accessSecret);
        }
        return null;

    }

    public function getUrlParamsFromMap_Vivo($paramsMap){
        ksort($paramsMap);
        $paramsMap = implode('&', array_map(
            function ($v, $k) { return sprintf("%s=%s", $k, $v); },
            $paramsMap,
            array_keys($paramsMap)));
        return $paramsMap;
    }

    public function hmacSHA256_Vivo($data, $key){
        $hash = hash_hmac('SHA256', $data, $key);
        return $hash;
    }

    public function sendMessTelegram($market,$sms){
//    public function sendMessTelegram(){
        $name = Auth()->user() ? Auth()->user()->name : "Auto";
        if($sms){
            return Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $market.' - '.$name.$sms,
            ]);
        }
        return;
    }

    //==========================================================================

    public function samsung($status_upload = null){

        if(isset(\request()->projectID)){
            $appsSamsung= MarketProject::where('id',\request()->projectID)->get();
        }else {
            $time =  Setting::first();
            $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
            $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;

            $appsSamsung = MarketProject::where('market_id', 3)
                ->where('status_upload','like','%'. $status_upload.'%')
                ->whereNotNull('appID')
                ->whereHas('dev', function ($query) {
                    return $query
                        ->whereNotNull('api_client_id')
                        ->where('api_client_id','<>','');
                })
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
                ->paginate($time->limit_cron);
        }

        if(count($appsSamsung)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }

        if($appsSamsung){
            $sms = $string = '';
            $status_cron =  'Mặc định';
            $status_app = 6;
            foreach ($appsSamsung as $appSamsung){
                $string .=  '<br/>'.'Dang chay: '. '-'.$appSamsung->project->projectname.'-';
                try{
                    if($appSamsung->appID){
                        if(!$appSamsung->dev){
                            $appSamsung->status_app = $status_app;
                            $status_cron = 'check';
                            $appSamsung->bot_time = time();
                            $appSamsung->save();
                            return  response()->json(['error'=>'Chưa có DEV', 'project'=>$appSamsung,'status'=>$status_cron]);
                        }else {
                            if(!$appSamsung->dev->api_client_id || !$appSamsung->dev->api_client_secret ){
                                $appSamsung->status_app = $status_app;
                                $status_cron = 'check';
                                $appSamsung->bot_time = time();
                                $appSamsung->save();
                                return  response()->json(['error'=>'DEV chưa có api_access_key', 'project'=>$appSamsung,'status'=>$status_cron]);
                            }else{
                                $appSamsung = $this->update_token_samsung($appSamsung);
                                $contentInfo = $this->contentInfo($appSamsung);
                                if ($contentInfo) {
                                    $contentStatus = $contentInfo[0]['contentStatus'];
                                    switch ($contentStatus){
                                        case 'REGISTERING':
                                            $status_app = 2;
                                            $status_cron = 'REGISTERING';
                                            break;
                                        case 'FOR_SALE':
                                            $status_app = 1;
                                            $status_cron = 'FOR_SALE';
                                            break;
                                        case 'SUSPENDED':
                                            $status_app = 5;
                                            $status_cron = 'SUSPENDED';
                                            break;
                                        case 'TERMINATED':
                                            $status_cron = 'TERMINATED';
                                            $status_app = 2;
                                            break;
                                        default:
                                            $status_app = 6;
                                            $status_cron = 'default';
                                            break;
                                    }

                                    $dataArr = [
                                        'status' => $status_cron
                                    ];

                                    $appSamsung->bot = json_encode($dataArr);
                                    $appSamsung->bot_appVersion =   $contentInfo[0]['binaryList'][0]['versionName'];
                                    $appSamsung->app_link =   'https://galaxystore.samsung.com/detail/'.$contentInfo[0]['binaryList'][0]['packageName'];
                                    $appSamsung->policy_link =  $contentInfo[0]['privatePolicyURL'];
                                    $appSamsung->status_app = $status_app;
                                }else{
                                    $appSamsung->status_app = $status_app;
                                    $status_cron = 'check';
                                }
                            }
                        }
                    }else{
                        $appSamsung->status_app = $status_app;
                        $status_cron = 'check';
                        $appSamsung->bot_time = time();
                        $appSamsung->save();
                        return  response()->json(['error'=>'Chưa có AppID', 'project'=>$appSamsung,'status'=>$status_cron]);
                    }


                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage(). '--- appsVivo: '.$appSamsung->id.':--' . $exception->getLine());
                }
                $appSamsung->bot_time = time();
                $appSamsung->save();
                $string .= $status_cron;
                if($status_app !=1){
                    $sms .= "\n<b>Project name: </b>"
                        . '<code>'.$appSamsung->project->projectname.'</code> - '
                        . '<code>'.$status_cron.'</code>';
                }
            }


            $this->sendMessTelegram('Samsung',$sms);
            if(\request()->return){
                return  response()->json(['success'=>'OK', 'project'=>$appSamsung,'status'=>$status_cron]);
            }else{
                echo '<br/><br/>';
                echo '<br/>' .'=========== Samsung ==============' ;
                echo '<br/><b>'.'Yêu cầu:';
                echo '<br/>&emsp;'.'- Project có AppID của Samsung.';
                echo '<br/>&emsp;'.'- Dev Samsung có Client ID và Client Secret'.'</b><br/><br/>';
                echo $string;
                return ;
            }
        }
    }

    function api_samsung(){
        $devs = Dev::where('market_id', 3)
            ->where(function($q)  {
                $q ->whereNotNull('api_client_secret')
                    ->where('api_client_secret','<>','');
            })
            ->get();

        foreach ($devs as $dev){
            $token = $dev->api_token;
            $account_id = $dev->api_client_id;
            $privateKey = $dev->api_client_secret ;

            if(!$this->check_token($token,$account_id)){
                $token = $this->get_token_samsung($account_id,$privateKey);
                $dev->api_token = $token;
                $dev->save();
            }
            $contentList =  $this->contentList($token,$account_id);
            $dataArr = [];
            $echo = $sms = '';
            foreach ($contentList as $content){
//                $a= $this->contentInfo($token,$account_id,'000006486573');

                $contentStatus = $content['contentStatus'];
                switch ($contentStatus){
                    case 'REGISTERING':
                        $status_app = 2;
                        break;
                    case 'FOR_SALE':
                        $status_app = 1;
                        break;
                    case 'SUSPENDED':
                        $status_app = 5;
                        $sms .= "\n<b>AppID: </b>"
                            . '<code>'.$content['contentId'].'</code> - '
                            . '<code>'.$content['contentName'].'</code> - '
                            . '<code>'.$content['contentStatus'].' </code>';
                        break;
                    case 'TERMINATED':
                        $status_app = 2;

                }
                $dataArr[] = [
                    'appID' => $content['contentId'],
                    'status_app' =>$status_app,
                    'bot_time' => time(),
                ];
                $echo .=  '<br/>'.'Dang chay: '.Carbon::now('Asia/Ho_Chi_Minh'). '-'. $content['contentId'].'--- '.$contentStatus;

            }

            $MarketProjectInstance = new MarketProject();
            $index = 'appID';
            batch()->update($MarketProjectInstance, array_unique($dataArr,SORT_REGULAR), $index);
            $this->sendMessTelegram('Samsung',$sms);
            return $echo;
        }
        return true;
    }

    function update_token_samsung($appSamsung){
        $token = $appSamsung->dev->api_token;
        $account_id = $appSamsung->dev->api_client_id;
        $privateKey = $appSamsung->dev->api_client_secret;
        $check = $this->check_token($token,$account_id);
        if(!$check){
            $token = $this->get_token_samsung($account_id,$privateKey);
            $appSamsung->dev->api_token = $token;
            $appSamsung->dev->save();
        }
        return $appSamsung;
    }

    function check_token($token,$account_id){
        $endpoint = "https://devapi.samsungapps.com/auth/checkAccessToken";
        $Headers = [
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=> $account_id,
        ];
        try {
            $response = Http::withHeaders($Headers)->get($endpoint);
            if ($response->successful()){
                $result = $response->json();
                $return = $result['ok'];
                return $return ;
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }

    }

    function get_token_samsung($account_id,$privateKey){
        $JWT = $this->gen_jwt($account_id,$privateKey);
        $Headers = [
            'Authorization'=> 'Bearer ' . $JWT,
            'content-type'=>'application/json',
        ];
        $endpoint = "https://devapi.samsungapps.com/auth/accessToken";
        try {
            $response = Http::withHeaders($Headers)->post( $endpoint);
            if ($response->successful()){
                $result = $response->json();
                $accessToken = $result['createdItem']['accessToken'];
                return $accessToken;
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
    }

    function gen_jwt($account_id,$privateKey)
    {
        $payload =  [
            "iss"=> $account_id,
            "scopes"=> ["publishing", "gss"],
            "iat"=> time(),
            "exp"=> time()+1200
        ];
        $jwt = JWT::encode($payload, $privateKey, 'RS256');
        return $jwt;
    }

    function  contentList($token,$account_id){
        $endpoint = "https://devapi.samsungapps.com/seller/contentList";
        $Headers = [
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=>$account_id,
        ];
        try {
            $response = Http::withHeaders($Headers)->get($endpoint);
            if ($response->successful()){
                $result = $response->json();
                return $result ;
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
    }

    function contentInfo($appSamsung){
        $result = '';
        $token = $appSamsung->dev->api_token;
        $account_id = $appSamsung->dev->api_client_id;
        $contentId = $appSamsung->appID;
        $endpoint = "https://devapi.samsungapps.com/seller/contentInfo?contentId=$contentId";
        $Headers = [
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=> $account_id,
        ];
        try {
            $response = Http::withHeaders($Headers)->get($endpoint);
            if ($response->successful()){
                $result = $response->json();

            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
        return $result ;
    }

    //==========================================================================

    public function amazon($status_upload = null){

        if(isset(\request()->projectID)){
            $appsAmazon = MarketProject::where('id',\request()->projectID)->get();
        }else {
            $time =  Setting::first();
            $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
            $status_upload = $_GET['status_upload'] ?? $status_upload;

            $appsAmazon = MarketProject::where('market_id', 2)
                ->where('status_upload','like','%'. $status_upload.'%')
//                ->whereNotNull('appID')
                ->whereHas('dev', function ($query) {
                    return $query
                        ->whereNotNull('api_client_id')
                        ->where('api_client_id','<>','');
                })
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
                ->paginate($time->limit_cron);

        }

        if(count($appsAmazon)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }

        /**
         *
        $Client_ID = 'amzn1.application-oa2-client.0b087c09c17542c98c82e5dc9a86a5f7';
        $Client_Secret = 'fd94434046e7531375dcdd1100570e7357c236636720f17c38cccd6d66021160';
         */

        if($appsAmazon){
            $sms = $string = '';
            $status_cron =  'Mặc định';
            $status_app = 6;
            foreach ($appsAmazon as $appAmazon){
                $string .=  '<br/>'.'Dang chay: '. '-'.$appAmazon->project->projectname.'-';
//                try{
//                    dd($appAmazon);
                    if($appAmazon->appID){
                        if(!$appAmazon->dev){
                            $appAmazon->status_app = $status_app;
                            $status_cron = 'check';
                            $appAmazon->bot_time = time();
                            $appAmazon->save();
                            return  response()->json(['error'=>'Chưa có DEV', 'project'=>$appAmazon,'status'=>$status_cron]);
                        }else {
                            if(!$appAmazon->dev->api_client_secret ){
                                $appAmazon->status_app = $status_app;
                                $status_cron = 'check';
                                $appAmazon->bot_time = time();
                                $appAmazon->save();
                                return  response()->json(['error'=>'DEV chưa có api_access_key', 'project'=>$appAmazon,'status'=>$status_cron]);
                            }else{
//                                $appAmazon = $this->get_token_amazon($appAmazon,'adx_reporting::appstore:marketer');
                                $appAmazon = $this->get_token_amazon($appAmazon,'appstore::apps:readwrite');
                                $report = $this->report_amazon($appAmazon);
                                dd($report);

//                                $createEdit = $this->createEdit_amazon($appAmazon);
//                                $getActiveEdit = $this->getActiveEdit_amazon($appAmazon);
                                $getPreviousEdit_amazon = $this->getPreviousEdit_amazon($appAmazon);
                                dd($getActiveEdit,123);


                                $contentInfo = $this->contentInfo($appAmazon);
                                if ($contentInfo) {
                                    $contentStatus = $contentInfo[0]['contentStatus'];
                                    switch ($contentStatus){
                                        case 'REGISTERING':
                                            $status_app = 2;
                                            $status_cron = 'REGISTERING';
                                            break;
                                        case 'FOR_SALE':
                                            $status_app = 1;
                                            $status_cron = 'FOR_SALE';
                                            break;
                                        case 'SUSPENDED':
                                            $status_app = 5;
                                            $status_cron = 'SUSPENDED';
                                            break;
                                        case 'TERMINATED':
                                            $status_cron = 'TERMINATED';
                                            $status_app = 2;
                                            break;
                                        default:
                                            $status_app = 6;
                                            $status_cron = 'default';
                                            break;
                                    }

                                    $dataArr = [
                                        'status' => $status_cron
                                    ];

                                    $appSamsung->bot = json_encode($dataArr);
                                    $appSamsung->bot_appVersion =   $contentInfo[0]['binaryList'][0]['versionName'];
                                    $appSamsung->app_link =   'https://galaxystore.samsung.com/detail/'.$contentInfo[0]['binaryList'][0]['packageName'];
                                    $appSamsung->policy_link =  $contentInfo[0]['privatePolicyURL'];
                                    $appSamsung->status_app = $status_app;
                                }else{
                                    $appSamsung->status_app = $status_app;
                                    $status_cron = 'check';
                                }
                            }
                        }
                    }else{
                        $appSamsung->status_app = $status_app;
                        $status_cron = 'check';
                        $appSamsung->bot_time = time();
                        $appSamsung->save();
                        return  response()->json(['error'=>'Chưa có AppID', 'project'=>$appSamsung,'status'=>$status_cron]);
                    }


//                }catch (\Exception $exception) {
//                    Log::error('Message:' . $exception->getMessage(). '--- appsAmazon: '.$appAmazon->id.':--' . $exception->getLine());
//                }
                $appSamsung->bot_time = time();
                $appSamsung->save();
                $string .= $status_cron;
                if($status_app !=1){
                    $sms .= "\n<b>Project name: </b>"
                        . '<code>'.$appSamsung->project->projectname.'</code> - '
                        . '<code>'.$status_cron.'</code>';
                }
            }


            $this->sendMessTelegram('Samsung',$sms);
            if(\request()->return){
                return  response()->json(['success'=>'OK', 'project'=>$appSamsung,'status'=>$status_cron]);
            }else{
                echo '<br/><br/>';
                echo '<br/>' .'=========== Samsung ==============' ;
                echo '<br/><b>'.'Yêu cầu:';
                echo '<br/>&emsp;'.'- Project có AppID của Samsung.';
                echo '<br/>&emsp;'.'- Dev Samsung có Client ID và Client Secret'.'</b><br/><br/>';
                echo $string;
                return ;
            }
        }
    }

    function get_token_amazon($appAmazon,$scope){
        $dev = $appAmazon->dev;
//        if($dev->api_expires_in_token < time()){
            $client_id  = $dev->api_client_id;
            $client_secret = $dev->api_client_secret;
            $Headers = [
                'Content-Type'=>'application/json',
            ];
            $dataArr = [
                'grant_type'=> 'client_credentials',
                'client_id'=> $client_id,
                'client_secret'=> $client_secret,
                'scope'=> $scope,

            ];
            $endpoint = "https://api.amazon.com/auth/o2/token";
            try {
                $response = Http::withHeaders($Headers)->post($endpoint,$dataArr);

                if ($response->successful()){
                    $result = $response->json();
                    $dev->api_token = $result['access_token'];
                    $dev->api_expires_in_token = time() + $result['expires_in'];
                    $dev->save();
                }
            }catch (\Exception $exception) {
                Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
            }
//        }
        return $appAmazon;
    }

    function getActiveEdit_amazon($appAmazon){
        $dev = $appAmazon->dev;
        $client_id  = $dev->api_client_id;
        $client_secret = $dev->api_client_secret;
        $token = $dev->api_token;
        $appID = $appAmazon->appID;
        $appID = 'amzn1.devportal.mobileapp.dddc43e94c21498fb8eb1410cc1dbec6';
//        dd(123);


        $Headers = [
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer  '.$token,
        ];
        $dataArr = [
            'grant_type'=> 'client_credentials',
            'client_id'=> $client_id,
            'client_secret'=> $client_secret,
            'scope'=> 'appstore::apps:readwrite',
        ];
        $endpoint = "https://developer.amazon.com/api/appstore/v1/applications/$appID/edits";

        try {
            $response = Http::withHeaders($Headers)->get($endpoint,$dataArr);
            if ($response->successful()){
                $result = $response->json();
                dd($result,11111111);
                $dev->api_token = $result['access_token'];
                $dev->api_expires_in_token = time() + $result['expires_in'];
                $dev->save();
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
        return $appAmazon;
    }

    function createEdit_amazon($appAmazon){
        $result = false;
        $dev = $appAmazon->dev;
        $client_id  = $dev->api_client_id;
        $client_secret = $dev->api_client_secret;
        $token = $dev->api_token;
        $appID = $appAmazon->appID;
//        $appID = 'amzn1.devportal.mobileapp.e17be2a9358b4339821a6555b2ed22fd';
        $appID = 'amzn1.devportal.mobileapp.dddc43e94c21498fb8eb1410cc1dbec6';

//        dd($appID);



        $Headers = [
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer  '.$token,
        ];
        $dataArr = [
            'grant_type'=> 'client_credentials',
            'client_id'=> $client_id,
            'client_secret'=> $client_secret,
            'scope'=> 'appstore::apps:readwrite',
        ];
        $endpoint = "https://developer.amazon.com/api/appstore/v1/applications/$appID/edits";

        try {
            $response = Http::withHeaders($Headers)->post($endpoint,$dataArr);
            dd($response,$response->json(),111);

            if ($response->successful()){
                $result = $response->json();
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
        return $result;
    }

    function getPreviousEdit_amazon($appAmazon){
        $result = false;
        $dev = $appAmazon->dev;
        $client_id  = $dev->api_client_id;
        $client_secret = $dev->api_client_secret;
        $token = $dev->api_token;
        $appID = $appAmazon->appID;
//        $appID = 'amzn1.devportal.mobileapp.e17be2a9358b4339821a6555b2ed22fd';
        $appID = 'amzn1.devportal.mobileapp.dddc43e94c21498fb8eb1410cc1dbec6';





        $Headers = [
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer  '.$token,
        ];
        $dataArr = [
            'grant_type'=> 'client_credentials',
            'client_id'=> $client_id,
            'client_secret'=> $client_secret,
            'scope'=> 'appstore::apps:readwrite',
        ];
        $endpoint = "https://developer.amazon.com/api/appstore/v1/applications/$appID/edits/previous";

        try {
            $response = Http::withHeaders($Headers)->get($endpoint,$dataArr);
            dd($response,$response->json(),'getPreviousEdit_amazon');

            if ($response->successful()){
                $result = $response->json();
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
        return $result;
    }

    function report_amazon($appAmazon){
        dd(12);
        $result = false;
        $dev = $appAmazon->dev;
        $token = $dev->api_token;

        if(isset($_GET['submonth'])){
            $month  =  Carbon::now()->subMonth($_GET['submonth'])->format('m');
            $year  =  Carbon::now()->subMonth($_GET['submonth'])->format('Y');

        }else{
            $month  =  Carbon::now()->format('m');
            $year    =  Carbon::now()->format('Y');

        }
        $endpoint = "https://developer.amazon.com/api/appstore/download/report/sales/$year/$month";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        try {
            $result = curl_exec($ch);

//        $file_handle = fopen($result, 'r');
        $zip = new ZipArchive;
        $zip->open($result);
        dd($zip,12322);

        echo $zip->getFromName('filename.txt');
        $zip->close();


            dd($file_handle,111111);

//        }catch (\Exception $exception) {
//            Log::error('Message:' . $exception->getMessage() . '--- report_amazon: ' . $exception->getLine());
//        }
        curl_close($ch);
        return $result;
    }

}
