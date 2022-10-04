<?php

namespace App\Http\Controllers;

use App\Models\Dev_Huawei;
use App\Models\Dev_Vivo;
use App\Models\Market_dev;
use App\Models\MarketProject;
use App\Models\ProjectModel;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Mavinoo\Batch\Batch;
use Nelexa\GPlay\GPlayApps;
use Telegram\Bot\Laravel\Facades\Telegram;

class CronProjectController extends Controller

{
    private  $domainHuawei = 'https://connect-api.cloud.huawei.com';

    // Vivo
    private $SIGN_METHOD_HMAC = "hmac-sha256";
    private $domainVivo = 'https://developer-api.vivo.com/router/rest';
    private $access_key =  '0987226aea07435e9b8a0fabcd38e7cd';
    private $accessSecret = '1bcc877c4e6a41a18a4ecc1419d07cbc';

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
            $huawei = $this->Huawei();
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project Huawei : ' . $exception->getLine());
        }
        try {
            $vivo   = $this->Vivo();
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Cron Project Vivo : ' . $exception->getLine());
        }

        if($chplay !== false   || $huawei !== false  ){
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
            $ch= '';
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
                }
                $ch .= '<br/>'.'Dang chay:  '.  '-'. $appChplay->id .'--'.$appChplay->status_app.' - '. Carbon::now('Asia/Ho_Chi_Minh');

            }
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

    public function Huawei($status_upload = null){
        $this->getTokenHuawei();
        $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;
        $time =  Setting::first();
        $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh');

        $appsHuawei = MarketProject::where('market_id', 7)
            ->where('status_upload','like','%'. $status_upload.'%')
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






        echo '<br/><br/>';
        echo '<br/>' .'=========== Huawei ==============' ;
        echo '<br/><b>'.'Yêu cầu:';
        echo '<br/>&emsp;'.'- Project có package Huawei.';
        echo '<br/>&emsp;'.'- Dev Huawei có Client ID và Client Secret'.'</b><br/><br/>';

        if(count($appsHuawei)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }
        if($appsHuawei){
            $ch = '';
            foreach ($appsHuawei->load('dev') as $appHuawei){

                $ch .=  '<br/>'.'Dang chay:  '.  '- '. $appHuawei->id .' - '. Carbon::now('Asia/Ho_Chi_Minh');
                $monthCron = isset($_GET['submonth']) ? Carbon::now()->subMonth($_GET['submonth'])->format('Ym') :  Carbon::now()->format('Ym');
                $dataArr = [];
                try {
                    $appIDs = $this->AppInfoPackageHuawei($this->domainHuawei,$appHuawei->dev->api_token,$appHuawei->dev->api_client_id,$appHuawei->package);
                    if($appIDs){
                        $appInfo    = $this->AppInfoHuawei($this->domainHuawei,$appHuawei->dev->api_token,$appHuawei->dev->api_client_id,$appIDs[0]->value);

                        if($appInfo){
                            $reportApp  = $this->reportAppHuawei($this->domainHuawei,$appHuawei->dev->api_token,$appHuawei->dev->api_client_id,$appIDs[0]->value);
                            $scoreApp  = $this->getScoreHuawei($this->domainHuawei,$appHuawei->dev->api_token,$appHuawei->dev->api_client_id,$appIDs[0]->value);
                            if($reportApp){
                                $file = $this->readCSV($reportApp['fileURL'],array('delimiter' => ','));
                                if($file){
                                    $dataArr[$monthCron] =[
                                        'Impressions' => $file['Impressions'],
                                        'Details_page_views' => $file['Details page views'],
                                        'Total_downloads' => $file['Total downloads'] ,
                                        'Uninstalls' => $file['Uninstalls (installed from AppGallery)'],
                                    ];
                                }
                            }
                            if ($appHuawei->bot){
                                $dataBot = json_decode($appHuawei->bot,true);
                                $data = $dataBot + $dataArr ;
                            }else{
                                $data = $dataArr;
                            }
                            $status = $appInfo['appInfo']['releaseState'];
                            switch ($status){
                                case 0:
                                    $status_app = 1;
                                    break;
                                case 1 || 11:
                                    $status_app = 4;
                                    break;
                                case 2 || 6 || 8 || 9:
                                    $status_app = 3;
                                    break;
                                case 3 || 4 || 5:
                                    $status_app = 6;
                                    break;
                                case 7:
                                    $status_app = 0;
                                    break;
                                case 10:
                                    $status_app = 2;
                                    break;
                            }

                            $data['status'] = $status;
                            $data['message'] = $appInfo['auditInfo']['auditOpinion'];
                            $data['updateTime'] = $appInfo['appInfo']['updateTime'];
                            krsort($data);

                            $appHuawei->bot_appVersion =   array_key_exists('versionNumber',$appInfo['appInfo']) ? $appInfo['appInfo']['versionNumber'] : null;
                            $appHuawei->policy_link =  array_key_exists('privacyPolicy',$appInfo['appInfo']) ? $appInfo['appInfo']['privacyPolicy'] : null;
                            $appHuawei->appID = $appIDs[0]->value ;
                            $appHuawei->status_app = $status_app;
                            $appHuawei->bot = $data;
                            $appHuawei->bot_score = $scoreApp['ret']['rtnCode'] == 0 ? $scoreApp['data']['score']['averageScore'] : 0 ;
                            $appHuawei->bot_installs = array_sum(array_column($data, 'Total_downloads'));;

                        }
                    }
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- cronHuawei: '.$appHuawei->id.'---' . $exception->getLine());
                }
                $appHuawei->bot_time = time();
                $appHuawei->save();
                $ch .= '--'. $appHuawei->status_app;
            }
            echo $ch;
            return true;
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

    public function setTokenHuawei(){

    }

    public function AppInfoHuawei($domain,$token,$clientID,$appID){
        $data = '';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
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

    public function AppInfoPackageHuawei($domain,$token,$clientID,$package){
        $data = '';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        $endpoint = "/api/publish/v2/appid-list?packageName=".$package;
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = json_decode(json_encode($response->json()));
                $data =  $data->appids;
            }

        }catch (\Exception $exception) {
            Log::error('Message: AppInfoPackageHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $data;
    }

    public function reportAppHuawei($domain,$token,$clientID,$appID){
        $data = '';
        if(isset($_GET['submonth'])){
            $startTime  =  Carbon::now()->subMonth($_GET['submonth'])->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->subMonth($_GET['submonth'])->endOfMonth()->format('Ymd');

        }else{
            $startTime  =  Carbon::now()->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->format('Ymd');
        }
        $lang='en-US';
        $endpoint = '/api/report/distribution-operation-quality/v1/appDownloadExport/'.$appID.'?language='.$lang.'&startTime='.$startTime.'&endTime='.$endTime.'&groupBy=businessType';
        $dataArr = [
            'Authorization'=> 'Bearer ' . $token,
            'client_id'=>$clientID,
            'Content-Type'=>'application/json',
        ];
        try {
            $response = Http::withHeaders($dataArr)->get($domain . $endpoint);
            if ($response->successful()){
                $data = $response->json();
            }
        }catch (\Exception $exception) {
            Log::error('Message: reportAppHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
        }
        return $data;
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


    public function getScoreHuawei($domain,$token,$clientID,$appID){
        $data = '';
        if(isset($_GET['submonth'])){
            $startTime  =  Carbon::now()->subMonth($_GET['submonth'])->startOfMonth()->format('Ymd');
            $endTime    =  Carbon::now()->subMonth($_GET['submonth'])->endOfMonth()->format('Ymd');

        }else{
            $startTime  =  Carbon::now()->startOfMonth()->timestamp;
            $endTime    =  Carbon::now()->timestamp;
        }

        $lang='US';
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

            }
        }catch (\Exception $exception) {
            Log::error('Message: reportAppHuawei:---' . $exception->getMessage() . '---' . $exception->getLine());
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

    public function searchForMonth($id, $array) {
        $a = [];
        if($array){
            foreach ($array as $key => $val) {
                if(isset($val['Month'])){
                    if ($val['Month'] === $id) {
                        $a[] = $key;
                    }
                }
            }
        }
        return $a;
    }

    public function Vivo(){
        $time =  Setting::first();
        $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
        $dev_vivo = ProjectModel::with(['dev_vivo'])
            ->whereHas('dev_vivo',function ($q){
                $q->where('vivo_dev_access_key', '<>', null)
                    ->where('vivo_dev_client_secret', '<>', null);
            })
            ->where('Vivo_package','<>', null)
            ->where('Vivo_bot->time_bot','<=',$timeCron)
            ->limit($time->limit_cron)
            ->get();

        echo '<br/><br/>';
        echo '<br/>' .'=========== Vivo ==============' ;
        echo '<br/><b>'.'Yêu cầu:';
        echo '<br/>&emsp;'.'- Project có Package của Vivo.';
        echo '<br/>&emsp;'.'- Dev Vivo có Client ID và Client Secret'.'</b><br/><br/>';

        if($dev_vivo){
            foreach ($dev_vivo as $dev){
                echo '<br/>'.'Dang chay:  '.  '-'. $dev->projectname .' - '. Carbon::now('Asia/Ho_Chi_Minh');

                try{
                    $data = $this->get_Vivo($dev->dev_vivo->vivo_dev_access_key,$dev->dev_vivo->vivo_dev_client_secret,$dev->Vivo_package);
                    $dataArr =[
                        'time_bot' => time(),
                        'versionName' => $data ? $data->versionName : 0 ,
                    ];
                    ProjectModel::updateOrCreate(
                        [
                            'projectid'=> $dev->projectid
                        ],
                        [
                            'Vivo_status' => $data ? $data->onlineStatus : 100,
                            'Vivo_bot' => json_encode($dataArr)
                        ]
                    );
                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- appsVivo: ' . $exception->getLine());
                }

            }
        }if(count($dev_vivo)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
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
            return $result->data;
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

    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
//        dd($activity);


//        Telegram::sendMessage([
////            'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
////            -692917665
//            'chat_id' => '-692917665',
//            'parse_mode' => 'HTML',
//            'text' => 'gjdfkgjdlf'
//        ]);

//        dd($activity);
        $getParams =  end($activity);
        $textRequest = $getParams['message']['text'];

        if (strpos($textRequest, '/getInfo') !== false) {
            [$key, $projectname] = explode(' ',$textRequest);
            $project = ProjectModel::with('da','matemplate')->where('projectname',$projectname)->first();
//            echo 'true';
//            dd($project);

            $text = "<b>Project name: </b>\n"
                . "<code>$project->projectname</code>\n"
                . "<b>Template: </b>\n"
                . "<pre>". $project->matemplate->template."</pre>\n"
                . "<b>DA: </b>\n"
                . "<pre>". $project->da->ma_da."</pre>\n"
                . "<b>Title: </b>\n"
                . "<pre>". $project->title_app."</pre>\n"
            ;

//            dd($text);
            Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_GROUP_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
            ]);
        }
    }


}
