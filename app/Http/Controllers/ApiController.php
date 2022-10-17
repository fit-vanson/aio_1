<?php

namespace App\Http\Controllers;

use App\Http\Resources\DaResource;
use App\Http\Resources\GaResource;
use App\Http\Resources\GmailDevResource;
use App\Http\Resources\KeystoresResource;
use App\Http\Resources\MarketDevResource;
use App\Http\Resources\MarketsResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TemplateResource;
use App\Models\Da;
use App\Models\Ga;
use App\Models\Ga_dev;
use App\Models\Keystore;
use App\Models\Market_dev;
use App\Models\MarketProject;
use App\Models\Markets;
use App\Models\ProfileV2;
use App\Models\Project;
use App\Models\Template;
use Google_Client;
use Google_Service_AndroidPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class ApiController extends Controller
{


    public function getProject(){
        if(\request()->projectID){
            if (\request()->marketID){
                $project = MarketProject::with('dev')->where('project_id',\request()->projectID)->where('market_id',\request()->marketID)->first();
            }else{
                $project = Project::with('markets')->find(\request()->projectID);
            }
            return response()->json($project);
        }else{
            return response()->json('msg:error');
        }
    }


    public function getDa(){
        $searchValue = \request()->q;
        $project = Da::latest()
            ->where('ma_da', 'like', '%' . $searchValue . '%')
            ->get();
        $result = DaResource::collection($project);
        return response()->json($result);
    }

    public function getTemplate(){
        $searchValue = \request()->q;
        $project = Template::latest('id')
            ->where('template', 'like', '%' . $searchValue . '%')
            ->where('status', 0)
            ->get();
        $result = TemplateResource::collection($project);
        return response()->json($result);
    }

    public function getDev(){
        $searchValue = \request()->q;
        $market_id = \request()->dev_id;
        $dev = Market_dev::latest('id')
            ->where('dev_name', 'like', '%' . $searchValue . '%')
//            ->orwhere('store_name', 'like', '%' . $searchValue . '%')
            ->where('market_id',  $market_id)
            ->whereIn('status',  [0,1])
            ->get();
        $result = MarketDevResource::collection($dev);
        return response()->json($result);
    }


    public function getKeystore(){
        $searchValue = \request()->q;

        $dev = Keystore::select('id','name_keystore')->latest('id')
            ->where('name_keystore', 'like', '%' . $searchValue . '%')
            ->get();
        $result = KeystoresResource::collection($dev);
        return response()->json($result);
    }


    public function getGa(){
        $searchValue = \request()->q;

        $dev = Ga::latest('id')
            ->where('ga_name', 'like', '%' . $searchValue . '%')
            ->get();
        $result = GaResource::collection($dev);
        return response()->json($result);
    }

    public function getMarket(){
        $searchValue = \request()->q;

        $dev = Markets::latest('id')
            ->where('market_name', 'like', '%' . $searchValue . '%')
            ->get();
        $result = MarketsResource::collection($dev);
        return response()->json($result);
    }

    public function getGmailDev(){
        $searchValue = \request()->q;

        $dev = Ga_dev::latest('id')
            ->where('gmail', 'like', '%' . $searchValue . '%')
            ->get();
        $result = GmailDevResource::collection($dev);
        return response()->json($result);
    }

    public function getProfile(){
        $searchValue = \request()->q;

        $dev = ProfileV2::latest('id')
            ->where('profile_name', 'like', '%' . $searchValue . '%')
            ->orwhere('profile_ho_va_ten', 'like', '%' . $searchValue . '%')
            ->orwhere('profile_cccd', 'like', '%' . $searchValue . '%')
            ->orwhere('profile_add', 'like', '%' . $searchValue . '%')
            ->get();
        $result = ProfileResource::collection($dev);
        return response()->json($result);
    }







    public function get_admod_list(Request $request)
    {

//                try {
        $client = $this->get_gclient($request);

//        $verify = $client->verifyIdToken();
//        $access_token = $client->getAccessToken();
        dd($client);

                    dd($client);

//                } catch (\Google_Exception $exception) {
//                    //  print_r($exception);
//                    //  exit();
//                }



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


    function get_get_token_callback(Request $request)
    {
//        $admod_account = AdModAccount::where('id', $request->state)->first();
        $g_client = $this->get_gclient($request);
        if (isset($request->code) && $request->code != '') {
            $accessToken = $g_client->fetchAccessTokenWithAuthCode($_GET['code']);
        }
        if (isset($accessToken)){
            $service = new Google_Service_AndroidPublisher($g_client);
//
            $package_name = "com.devprotechnology.gallerylock";
            $token = $accessToken['access_token'];
            $optParams = array(
//                "maxResults"=>10,
//                "startIndex"=>0,
//                "token"=> $accessToken['access_token'],
//                "translationLanguage"=>"en"
            );


//            $url = "https://www.googleapis.com/androidpublisher/v3/applications/$package_name/reviews?access_token=$token";
            $response = $service->reviews->listReviews($package_name,$optParams);
//            $response = Http::get($url);

            dd($response);
        }

        $authUrl = $g_client->createAuthUrl();


        echo '<META http-equiv="refresh" content="0;URL=' . $authUrl . '">';
        return;
        }


    function get_get_ga_token(Request $request)
    {

        if (isset($request->id)) {
            $admod_account = AdModAccount::where('id', $request->id)->first();
        }

        $g_client = $this->get_gclient($request, $admod_account);

        if (isset($request->code) && $request->code != '') {
            $code = $request->code;
            $auth_result = $g_client->authenticate($code);

            Log::debug("auth_result: " . $auth_result['auth_result']);
            $access_token = $g_client->getAccessToken();
            Log::debug('access_token:' . $access_token);
//            $service = new \Google_Service_AdSense($g_client);
            $service = new Google_Service_AdMob($g_client);
            $acc_service = $this->get_user($service);

            $user_name = $acc_service["name"];
            $user_id = $acc_service["id"];
            $admod_account = AdModAccount::where('admod_pub_id', $user_id)->first();
            if (count($admod_account) > 0) {
                $admod_account->access_token_full = \GuzzleHttp\json_encode($access_token);
                $admod_account->access_token = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($access_token))->access_token;
                $admod_account->save();
            } else {
                AdModAccount::create([
                    'admod_pub_id' => $user_id,
                    'adsmod_name' => $user_name,
                    'access_token_full' => \GuzzleHttp\json_encode($access_token),
                    'access_token' => \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($access_token))->access_token
                ]);
            }
            echo '<META http-equiv="refresh" content="0;URL=' . url("/api/admod") . '">';
            return;

        }
        $authUrl = $g_client->createAuthUrl();

        echo '<META http-equiv="refresh" content="0;URL=' . $authUrl . '">';
        return;
    }
    function get_user($service)
    {
        try {
            $info = $service->accounts;
            $item = $info->listAccounts();
            $acc = $item['account'][0];
            $result["id"] = preg_replace("/[^0-9]/", "", $acc['name']);
            $result["name"] = $acc->getName();
            return $result;
        } catch (\Exception $exception) {
            echo 'get_user ==> ' . $exception->getMessage();
        }
    }

    function get_gclient($request)
    {
        $client = new \Google_Client();
        try {
            $client_id = '1081368451259-hfk41u1dgtp99mp2806p6vg0g5c9738q.apps.googleusercontent.com';
            $client_secret = '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';
            $package_name = "com.devprotechnology.gallerylock";


            $redirect_uri = URL::current();
            $redirect_uri = substr($redirect_uri, 0, strrpos($redirect_uri, '/'));
            $redirect_uri .= "/get-token-callback";


            $client = new Google_Client();
            $client->setApplicationName($package_name);
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->setRedirectUri($redirect_uri);
            $client->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
            $client->setAccessType('offline');

        } catch (\Google_Exception $exception) {
            print_r($exception);
        }

        return $client;

    }


    public function getReview(){

        $client_id = '1081368451259-hfk41u1dgtp99mp2806p6vg0g5c9738q.apps.googleusercontent.com';
        $client_secret = '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';
        $redirect_uri = URL::current();

        $redirect_uri = substr($redirect_uri, 0, strrpos($redirect_uri, '/'));
        $redirect_uri .= "/getReview";
        $package_name = "com.devprotechnology.gallerylock";

        $client = new Google_Client();
        $client->setApplicationName($package_name);
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
        $client->setAccessType('offline');

        if(!$client->getAccessToken() && !isset($_GET['code'])) {
            $authUrl = $client->createAuthUrl();
            echo "<a class='login' href='$authUrl'>Connect Me!</a>";
        }

        if(isset($_GET['code'])){
            $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        }

        if (isset($accessToken)){
            print "<a class='logout' href='".$_SERVER['PHP_SELF']."?logout=1'>LogOut</a><br>";

            $service = new Google_Service_AndroidPublisher($client);

            $optParams = array(
                "maxResults"=>10,
                "startIndex"=>0,
                "token"=> $accessToken['access_token'],
                "translationLanguage"=>"en"
            );

            try {
                $response = $service->reviews->listReviews($package_name,$optParams);
                echo "<pre>".print_r($response,true)."</pre>";
            }
            catch (Google_Service_Exception $e) {
                $json = $e->getMessage();
                echo $json;
            }
        }




    }

}
