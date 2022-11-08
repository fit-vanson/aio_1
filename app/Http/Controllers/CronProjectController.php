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

    public function Huawei($status_upload = null){
        $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;
        $time =  Setting::first();
        $timeCron = Carbon::now()
            ->setTimezone('Asia/Ho_Chi_Minh')
            ->subMinutes($time->time_cron)
            ->timestamp;
        $this->getTokenHuawei();

        if(isset(\request()->projectID)){
            $appsHuawei = MarketProject::where('id',\request()->projectID)->get();
        }else {
            $appsHuawei = MarketProject::where('market_id', 7)
//            ->where('appID','105596233')
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
            $ch = '';
            $sms =  '';
            $status_cron =  'Mặc định';
            foreach ($appsHuawei as $appHuawei){
                $ch .=  '<br/>'.'Dang chay:  '.  '- '.$appHuawei->project->projectname.' - '. Carbon::now('Asia/Ho_Chi_Minh');
                $monthCron = isset($_GET['submonth']) ? Carbon::now()->subMonth($_GET['submonth'])->format('Ym') :  Carbon::now()->format('Ym');
                $dataArr = [];
                try {
                    if(!$appHuawei->dev){
                        return response()->json(['error'=>'Chưa có DEV']);
                    }else{
                        if(!$appHuawei->dev->api_token){
                            return response()->json(['error'=>'DEV chưa có Token']);
                        }else{
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
                                            $status_cron = 'released';
//                                    $sms .= "\n<b>Project name: </b>"
//                                        . '<code>'.$appHuawei->project->projectname.'</code> - '
//                                        . "<code> ok  </code>";
                                            break;
                                        case 1 :
                                            $status_app = 4;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Release rejected  </code>";
                                            break;
                                        case  11:
                                            $status_app = 4;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Release canceled  </code>";
                                            break;
                                        case 2 :
                                            $status_app = 3;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Removed (including forcible removal)  </code>";
                                            break;
                                        case 6 :
                                            $status_app = 3;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Removal requested  </code>";
                                            break;
                                        case 8 :
                                            $status_app = 3;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Update rejected  </code>";
                                            break;
                                        case 9:
                                            $status_app = 3;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Removal rejected  </code>";
                                            break;
                                        case 3:
                                            $status_app = 6;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Releasing  </code>";
                                            break;
                                        case  4 :
                                            $status_app = 6;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Reviewing  </code>";
                                            break;
                                        case  5:
                                            $status_app = 6;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Updating  </code>";
                                            break;
                                        case 7:
                                            $status_app = 0;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> Draft  </code>";
                                            break;
                                        case 10:
                                            $status_app = 2;
                                            $status_cron = 'Release rejected';
                                            $sms .= "\n<b>Project name: </b>"
                                                . '<code>'.$appHuawei->project->projectname.'</code> - '
                                                . "<code> UnPublish  </code>";
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
                                    $appHuawei->bot_installs = array_sum(array_column($data, 'Total_downloads'));
                                }
                            }
                            $appHuawei->bot_time = time();
                            $appHuawei->save();
                            $ch .= '--'. $status_cron .'---';
                        }

                    }


                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage() . '--- cronHuawei: '.$appHuawei->id.'---' . $exception->getLine());
                    return response()->json(['error'=>$exception->getMessage()]);
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
                echo '<br/><b>&emsp;'.'- Trạng thái:'.'</b><br/>';
                echo '<br/>&emsp;'.'0: released &emsp; 1: release rejected &emsp; 2: removed (including forcible removal) &emsp; 3: releasing &emsp; 4: reviewing &emsp; 5: updating &emsp;';
                echo '<br/>&emsp;'.'6: removal requested &emsp; 7: draft &emsp; 8: update rejected &emsp; 9: removal rejected &emsp; 10: removed by developer &emsp; 11: release canceled &emsp;'.'<br/>';
                echo $ch;
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

    public function Vivo($status_upload = null){

        $time =  Setting::first();
        $timeCron = Carbon::now()->subMinutes($time->time_cron)->setTimezone('Asia/Ho_Chi_Minh')->timestamp;
        $status_upload = isset($_GET['status_upload']) ? $_GET['status_upload'] : $status_upload;


        if(isset(\request()->projectID)){
            $appsVivo = MarketProject::where('id',\request()->projectID)->get();
        }else {
            $appsVivo = MarketProject::with('dev')
                ->where('market_id', 6)
                ->where('status_upload','like','%'. $status_upload.'%')
                ->whereHas('dev', function ($query) {
                    return $query
                        ->whereNotNull('api_access_key')
                        ->where('api_access_key','<>','');
//                    ->where('dev_name','DEV V1');
                })
                ->where(function ($q) use ($timeCron) {
                    $q->where('bot_time', '<=', $timeCron)
                        ->orWhere('bot_time', null);
                })
//            ->get();
                ->paginate($time->limit_cron);

        }

        if(count($appsVivo)==0){
            echo 'Chưa đến time cron'.PHP_EOL .'<br>';
            return false;
        }

        if($appsVivo){
            $sms = $ch = '';
            $status_cron =  'Mặc định';
            foreach ($appsVivo as $appVivo){
                $ch .=  '<br/>'.'Dang chay: '. '-'. $appVivo->id .' - '.$appVivo->project->projectname.'---'. Carbon::now('Asia/Ho_Chi_Minh');
                try{
                    if(!$appVivo->dev){
                        return response()->json(['error'=>'Chưa có DEV']);
                    }else {
                        if(!$appVivo->dev->api_access_key || !$appVivo->dev->api_client_secret ){
                            return response()->json(['error'=>'DEV chưa có api_access_key ']);
                        }else{
                            $get_Vivo = $this->get_Vivo($appVivo->dev->api_access_key, $appVivo->dev->api_client_secret, $appVivo->package);
                            if ($get_Vivo->data) {
                                $status = $get_Vivo->data->onlineStatus;
                                switch ($status) {
                                    case 0:
                                        $status_app = 2;
                                        $status_cron = 'Unpublished';
                                        $sms .= "\n<b>Project name: </b>"
                                            . '<code>' . $appVivo->project->projectname . '</code> - '
                                            . "<code>Unpublished </code>";
                                        break;
                                    case 1 || 3 :
                                        $status_app = 1;
                                        $status_cron = 'Published';
                                        break;
                                    case 2:
                                        $status_app = 3;
                                        $status_cron = 'Removed';
                                        $sms .= "\n<b>Project name: </b>"
                                            . '<code>' . $appVivo->project->projectname . '</code> - '
                                            . "<code>Removed  </code>";
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
                                $appVivo->status_app = 6;
                                $status_cron = $get_Vivo->subMsg;
                            }
                        }
                    }

                }catch (\Exception $exception) {
                    Log::error('Message:' . $exception->getMessage(). '--- appsVivo: '.$appVivo->id.':--' . $exception->getLine());
                }
                $appVivo->bot_time = time();
                $appVivo->save();
                $ch .= '-'.$status_cron;
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
                echo $ch;
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
//        $activity = Telegram::getUpdates();
        if($sms){
            Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $market.$sms,
            ]);
        }
        return true;

    }

    public function samsung($status_upload = null){


        if(isset(\request()->projectID)){
            return $this->checkStatus(\request()->projectID);
        }else{
            echo '<br/><br/>';
            echo '<br/>' .'=========== Samsung ==============' ;
            echo '<br/><b>'.'Yêu cầu:';
            echo '<br/>&emsp;'.'- Project có AppID của Samsung.';
            echo '<br/>&emsp;'.'- Dev Samsung có Client ID và Client Secret'.'</b><br/><br/>';
            echo $this->api_samsung();
            return;
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

    function contentInfo($token,$account_id,$contentId){
        $endpoint = "https://devapi.samsungapps.com/seller/contentInfo?contentId=$contentId";
        $Headers = [
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=> $account_id,
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

    function checkStatus($projectID){
        $project = MarketProject::findorfail($projectID)->load('dev','project');
        $market = $project->market_id;
        if ($project->dev){
            $token = $project->dev->api_token;
            $account_id = $project->dev->api_client_id;

            switch ($market){
                case 3:
                    if($project->appID){
                        $privateKey = $project->dev->api_client_secret;
                        $contentId = $project->appID;
                        if(!$this->check_token($token,$account_id)){
                            $token = $this->get_token_samsung($account_id,$privateKey);
                            $project->dev->api_token = $token;
                            $project->dev->save();
                        }
                        $token = $project->dev->api_token;
                        $appInfo = $this->contentInfo($token,$account_id,$contentId);
                        $contentStatus = $appInfo[0]['contentStatus'];
                        switch ($contentStatus){
                            case 'REGISTERING':
                                $status_app = 2;
                                break;
                            case 'FOR_SALE':
                                $status_app = 1;
                                break;
                            case 'SUSPENDED':
                                $status_app = 5;
                                break;
                            case 'TERMINATED':
                                $status_app = 4;
                        }
                        $project->status_app = $status_app;
                        $project->save();
                        $sms = "\n<b>AppID: </b>"
                            . '<code>'.$appInfo[0]['contentId'].'</code> - '
                            . '<code>'.$project->project->projectname.'</code> - '
                            . '<code>'.$appInfo[0]['contentStatus'].' </code>';
                        $this->sendMessTelegram('Samsung',$sms);
                        $result = response()->json(['success'=>'OK', 'project'=>$project]);
                    }else{
                        $result = response()->json(['error'=>'Chưa có AppID Samsung']);
                    }
                    break;

            }






        }else{
            $result = response()->json(['error'=>'Chưa có DEV']);
        }






//        switch ($market){
//            case 3:
//                if ($project->dev){
//                    if($project->appID){
//                        $token = $project->dev->api_token;
//                        $account_id = $project->dev->api_client_id;
//                        $privateKey = $project->dev->api_client_secret;
//                        $contentId = $project->appID;
//                        if(!$this->check_token($token,$account_id)){
//                            $token = $this->get_token_samsung($account_id,$privateKey);
//                            $project->dev->api_token = $token;
//                            $project->dev->save();
//                        }
//                        $appInfo = $this->contentInfo($token,$account_id,$contentId);
//                        $contentStatus = $appInfo[0]['contentStatus'];
//                        switch ($contentStatus){
//                            case 'REGISTERING':
//                                $status_app = 2;
//                                break;
//                            case 'FOR_SALE':
//                                $status_app = 1;
//                                break;
//                            case 'SUSPENDED':
//                                $status_app = 5;
//                                break;
//                            case 'TERMINATED':
//                                $status_app = 4;
//                        }
//                        $project->status_app = $status_app;
//                        $project->save();
//                        $sms = "\n<b>AppID: </b>"
//                            . '<code>'.$appInfo[0]['contentId'].'</code> - '
//                            . '<code>'.$project->project->projectname.'</code> - '
//                            . '<code>'.$appInfo[0]['contentStatus'].' </code>';
//                        $this->sendMessTelegram('Samsung',$sms);
//                        $result = response()->json(['success'=>'OK', 'project'=>$project]);
//                    }else{
//                        $result = response()->json(['error'=>'Chưa có AppID Samsung']);
//                    }
//                }else{
//                        $result = response()->json(['error'=>'Chưa có DEV']);
//                }
//                break;
//            case 7:
//
//                dd($project);
//                dd(12);
//                break;
//
//        }

        return $result;
    }


}
