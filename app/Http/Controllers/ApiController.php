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
use App\Models\Dev;
use App\Models\Ga;
use App\Models\Ga_dev;
use App\Models\GoogleReview;
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
use Illuminate\Support\Facades\DB;
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
        $dev = Dev::find($request->state);
        $g_client = $this->get_gclient($request,$dev);
        if (isset($request->code) && $request->code != '') {
            $code = $request->code;
            $accessToken = $g_client->fetchAccessTokenWithAuthCode($code);
        }
        if (isset($accessToken)){
            $dev->api_token = $accessToken['access_token'];
            $dev->api_expires_in_token = time()+ $accessToken['expires_in'] ;
            $dev->api_refresh_token = @$accessToken['refresh_token'] ;
            $dev->save();
            return "<script>window.close();</script>";
        }

        $authUrl = $g_client->createAuthUrl();
        echo '<META http-equiv="refresh" content="0;URL=' . $authUrl . '">';
        return;
        }


    function get_token(Request $request)
    {
        if (isset($request->id)) {
            $dev = Dev::findorfail($request->id);
        }
        $g_client = $this->get_gclient($request, $dev);
        if($g_client){
            if (isset($request->code) && $request->code != '') {
                $code = $request->code;
                $accessToken = $g_client->fetchAccessTokenWithAuthCode($code);
            }
            if (isset($accessToken)){
                $dev->api_token = $accessToken['access_token'];
                $dev->api_expires_in_token = time()+ $accessToken['expires_in'] ;
                $dev->api_refresh_token = $accessToken['refresh_token'] ;
                $dev->save();
                return "<script>window.close();</script>";
            }
            $authUrl = $g_client->createAuthUrl();
            echo '<META http-equiv="refresh" content="0;URL=' . $authUrl . '">';
            return;
        }else{
            return 'API Client ID: null <br> API Client Secret: null';
        }

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

    function get_gclient($request, $dev)
    {

        $client_id = $dev->api_client_id;
        $client_secret = $dev->api_client_secret;

        if(empty($client_id) || empty($client_secret)){
            return false;
        }else{

            $redirect_uri = $_SERVER['APP_URL'];
            $redirect_uri .= "/api/get-token-callback";
            $client = new Google_Client();

            $client->setApplicationName('VIETMMO GOOGLEAPI');
            $client->setClientId($client_id);
            $client->setState(array('a_id' => $dev->id));
            $client->setClientSecret($client_secret);
            $client->setRedirectUri($redirect_uri);
            $client->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
            $client->setAccessType('offline');
//            $client->setIncludeGrantedScopes(true);
            return $client;

        }

    }

    public function getReview(Request $request,$id){

        $dev = Dev::findorfail($id);
        $client = $this->get_gclient($request,$dev);
        $service = new Google_Service_AndroidPublisher($client);
        $refresh_token = $dev->api_refresh_token;
        if(isset($refresh_token) && $dev->api_expires_in_token < time()){
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
            $accessToken = $client->getAccessToken();
            $dev->api_token = $accessToken['access_token'];
            $dev->api_expires_in_token = time()+ $accessToken['expires_in'] ;
            $dev->save();
        }
        $token = $dev->api_token;
        $optParams = array(
            "maxResults"=>50,
            "startIndex"=>0,
            "token"=> $client->setAccessToken($token),
//                "translationLanguage"=>"en"
        );

        $dataArr = [];
        foreach ($dev->projects_market as $project){
            $package_name = $project->package;
            try {
                $response = $service->reviews->listReviews($package_name,$optParams);
                $reviews =  $response->getReviews();

                if ($reviews){

                    $data = [];
                    foreach ($reviews as $review){
                        $comments   = $review->getComments();
//                        $dataArr[] = [
//                            'project_id' =>  $project->project_id,
//                            'project_market_id' =>  $project->id,
//                            'package' =>  $project->package,
//                            'reviewId' =>  $review->reviewId,
//                            'authorName' =>  $review->authorName,
//                            'userComment' =>  $comments[0]->getUserComment()->getText(),
//                            'reviewerLanguage' =>  $comments[0]->getUserComment()->reviewerLanguage,
//                            'thumbsDownCount' =>  $comments[0]->getUserComment()->getThumbsDownCount(),
//                            'thumbsUpCount' =>  $comments[0]->getUserComment()->getThumbsUpCount(),
//                            'starRating' =>  $comments[0]->getUserComment()->getStarRating(),
//                            'deviceMetadata' =>  json_encode($comments[0]->getUserComment()->getDeviceMetadata()),
//                            'lastModifiedUser' =>  $comments[0]->getUserComment()->getLastModified()->getSeconds(),
//                            'developerComment' => isset($comments[1]) ?  $comments[1]->getDeveloperComment()->getText() : null,
//                            'lastModifiedDeveloper' => isset($comments[1]) ?  $comments[1]->getDeveloperComment()->getLastModified()->getSeconds() : null,
//                        ];
                        GoogleReview::updateorCreate(
                            [
                                'reviewId' =>  $review->reviewId,
                            ],
                            [
                                'project_id' =>  $project->project_id,
                                'project_market_id' =>  $project->id,
                                'package' =>  $project->package,
                                'authorName' =>  $review->authorName,
                                'userComment' =>  $comments[0]->getUserComment()->getText(),
                                'reviewerLanguage' =>  $comments[0]->getUserComment()->reviewerLanguage,
                                'thumbsDownCount' =>  $comments[0]->getUserComment()->getThumbsDownCount(),
                                'thumbsUpCount' =>  $comments[0]->getUserComment()->getThumbsUpCount(),
                                'starRating' =>  $comments[0]->getUserComment()->getStarRating(),
                                'deviceMetadata' =>  json_encode($comments[0]->getUserComment()->getDeviceMetadata()),
                                'lastModifiedUser' =>  $comments[0]->getUserComment()->getLastModified()->getSeconds(),
                                'developerComment' => isset($comments[1]) ?  $comments[1]->getDeveloperComment()->getText() : null,
                                'lastModifiedDeveloper' => isset($comments[1]) ?  $comments[1]->getDeveloperComment()->getLastModified()->getSeconds() : null,
                            ]
                        );
                    }
                }
            }catch (\Exception $exception) {
                Log::debug('Message -  getReview: ' . $exception->getMessage() . '---' . $exception->getLine());
            }
        }
        dd(1);
    }


    public function postReview(Request $request,$id){
        $dev = Dev::findorfail($id);
        $client = $this->get_gclient($request,$dev);


        $service = new Google_Service_AndroidPublisher($client);
        $refresh_token = $dev->api_refresh_token;
        if(isset($refresh_token) && $dev->api_expires_in_token < time()){
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
            $accessToken = $client->getAccessToken();
            $dev->api_token = $accessToken['access_token'];
            $dev->api_expires_in_token = time()+ $accessToken['expires_in'] ;
            $dev->save();
        }
        $token = $dev->api_token;
        $optParams = array(
            "maxResults"=>50,
            "startIndex"=>0,
            "token"=> $client->setAccessToken($token),
//                "translationLanguage"=>"en"
        );


        $package_name = "com.devpro.technology.imagevideorecovery";
        $reviewID = '4cdb9156-e9b5-4a74-b54e-b10114b6b1e4';

        $dataArr = array(
            'replyText' => 'Thanks for your feedback!'
        );
//        dd($reply);
//
//
//
        $endpoint = "https://www.googleapis.com/androidpublisher/v3/applications/$package_name/reviews/$reviewID:reply?access_token=$token";


        $response = Http::withHeaders([
            'Content-Type: application/json',
        ])->post($endpoint, $dataArr);

//        $response = $service->reviews->reply($package_name,$reviewID,$reply,$optParams);
        dd($response);
    }
}
