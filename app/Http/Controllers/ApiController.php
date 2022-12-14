<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApktoolResource;
use App\Http\Resources\DaResource;
use App\Http\Resources\GaResource;
use App\Http\Resources\GmailDevResource;
use App\Http\Resources\KeystoresResource;
use App\Http\Resources\MarketDevResource;
use App\Http\Resources\MarketsResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TemplateResource;
use App\Models\ApkTools;
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
use App\Models\ProjectHasLang;
use App\Models\Template;
use CURLFile;
use Firebase\JWT\JWT;
use Google_Client;
use Google_Service_AndroidPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


use SellingPartnerApi\Api\AuthorizationV1Api;
use SellingPartnerApi\Api\TokensV20210301Api;
use SellingPartnerApi\Authentication;
use SellingPartnerApi\Configuration;
use SellingPartnerApi\Endpoint;


class ApiController extends Controller
{


    public function getProject(){
        if(\request()->projectID){
            if (\request()->marketID){
                $project = MarketProject::with('dev','project.ma_template')->where('project_id',\request()->projectID)->where('market_id',\request()->marketID)->first();
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



    public function getApktool(){
        $searchValue = \request()->q;
        $project = ApkTools::latest('id')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->get();
        $result = ApktoolResource::collection($project);
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
            $client->setApprovalPrompt("force");
            $client->setIncludeGrantedScopes(true);
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
                        $dataArr[] = [
                            'project_id' =>  $project->project_id,
                            'project_market_id' =>  $project->id,
                            'package' =>  $project->package,
                            'reviewId' =>  $review->reviewId,
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
                        ];
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
        return Response::json($dataArr);
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

    public function get_postReview(Request $request){


        $review = GoogleReview::find($request->id);

        $reviewID = $review->reviewId;
        $package = $review->package;
        $dev = $review->project_market->dev;

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

        $dataArr = array(
            'replyText' => $request->replyText
        );
        $url = "https://www.googleapis.com/androidpublisher/v3/applications/$package/reviews/$reviewID:reply?access_token=$token";
        $response = Http::withHeaders([
            'Content-Type: application/json',
        ])->post($url, $dataArr);

        $review->developerComment = $request->replyText;
        $review->lastModifiedDeveloper = time();

        if ($response->successful()){
            $review->status = 0;
            $review->save();
            return response()->json(['success'=>'Th??nh c??ng']);
        }else if($response->failed()){
            $review->status = 1;
            $review->message = $response->json();
            $review->save();
            return response()->json($response->json());
        }
    }

    public function ReviewForDevCHplay(Request $request){
        $devs = Dev::where('market_id',1)
            ->where('api_client_id','<>',null)
            ->where('api_client_secret','<>',null)
            ->where('api_token','<>',null)
            ->get();
        foreach ($devs as $dev){
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
                            $dataArr[] = [
                                'project_id' =>  $project->project_id,
                                'project_market_id' =>  $project->id,
                                'package' =>  $project->package,
                                'reviewId' =>  $review->reviewId,
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
                            ];
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
                    Log::debug('Message -  getReview: '.$dev->dev_name.'---' . $exception->getMessage() . '---' . $exception->getLine());
                }
            }
        }
        return Response::json($dataArr);

    }



    public function samsung(Request $request){



        $privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA+dH2ivRTpwBMZ23l9DouDYShDOKkc+W/5Ra5iyCB0slA4v60
91Wnt5fT4D3siDNCuRkiYqYkfOoouNOSaNLmZG9rv5tvh5nw420fBUDS7TQlZm9P
z+gf7N5a/1iW0uYlhUQufbAM2Tat9GI0klTCNsE28QVU1Ttl8J1X/k3RuLZqTyKD
wuG6lp6RTj5I9MpFSmA0v8GGkkcd9galYpxaRm9A9fOqdkrcAAfnSPFJKOUWJEBP
Ia0PGYHjU59n0q2D3/GSpkqH/U8qUlI/72CDnALj/xjtnEbjIZWjRv6JXFiuwhjy
pRWy+o2kYJv+HSpQvaspLdpb0+KM2z4OsPu5lQIDAQABAoIBAF8dSbjt/UuAGZbn
iL3LyOzsqkS1pddaig028b2+yq7uYP4L6+qfehO0gr7F1OCmY6kFoMneZ9YHcSmt
o/i1E3L52RRCodwHCGgOi9j8LVKSoAq4JrMJtd4BarP9jq8NYQu0Qd8owDuTTffV
zB5Klwcx2TE6zmnBC7bosS/pgQfJ623Nk86Qv7klQcJmgRYsRLyupfU5zlbzINPU
y79u9YwIRjRNa6WCd5mv7P+A+mi4MZkzsSg5tMzVqWMFe5DhT3m6m1yfe5zgS7ih
6GDRdEgmHNUXW/jnNynJq12Ti73QAOdYYHF9MfuP8mYwsLxxq8tz/Ne1GCfqmjib
APlqfaECgYEA/W/czhSGw94treC+4fJTwvLEmSHBfRTlwWLLz+CwR3ZPSVD7kDJ4
9mwdZ8r0c7H3fy7VtaGwDFNhVvERDtSB9Ll1rg1regA2OWwF4DSjrivPu4PKqqCK
swmiAtMNDr6S71FgSiN941KPCXE4QSs7ve+FscaL5tuZiBkCxbGiwCMCgYEA/Fi8
n6RLjPwvm137d8mcPCf31gRFcCk8WM9qxYqDNZVMLyn6z8pdS0+24nvK1DdKj0+l
wNtktJGngI8Nvx06P1b4bzvuJ41Zh6qm+ivnYTMxDhoVNPIEalu9PRCO+Ot2JU4n
TQ16OkRqJdRauHXOUIQdVnPYLzwNko5OEJCW3ucCgYEAvFKyViRknAluEiXOUeGb
ImL5efzeZY7wx4odfyQseX3NnuJhfJ40ypA+LZFfotUc31IzFdvHEPGohE1v6oA4
7VweuS5ZrfeYU4UUvK0A7/y4SVO+dpoDVtUSoVyo+Ererpzem1jSQ+hmR5LtRWfV
5ealhxvNe8e0x7AmIjdEg9cCgYEAufYKyvqgUn1l9/ECZ/xDDnHFygnLwiQhPLFd
1cWFe+9R/U/KbWaL6fwMokrn5gv4/jOLytvjEs5jyfGiB7zaN+M3oYFgt/UKjVfN
RX8lPBQlimbeSe4wItEIW//f3MBoiIVXoQjVkirorogXcugd6mfx1sv3/JccyWvl
S3/CLvECgYEA0p8rY3B7X00JXrhipxOd8jc3+xhKYtxRGkh18YfXrLYsWRk2Z82R
rA/b/gGjcq1n6aFWcz7M7XHnRmWzSi4tNOBsO7XUVRIV0dbXDOd8No8gCgqIu+65
BKUyc6NW6/fEZsTTUK3dMDbOJLU42oZnnLnw3bZe37G09/EmR54iDkA=
-----END RSA PRIVATE KEY-----';

        $account_id = 'df95b1b6-3c61-46d7-81d2-9afe2f6da775';
        $token = '0CgHMdGsd0bvfgzaqw1t2fZz';
//        if(!$this->check_token($token,$account_id)){
            $token = $this->get_token_samsung($account_id,$privateKey);
//        }

//        dd($token);

//        $contentList = $this->contentList($token,$account_id);
//
//        dd($contentList);


        $contentId = '000006482916';
        $dir_apk = 'https://api.vietmmo.net/build/DA219/DA219-57/CHPLAY_DA219-57_TA111_V4_Ver_5_2910_2022_align_k16006.apk';
        $contentInfo = $this->contentInfo($token,$account_id,$contentId);

        dd($contentInfo);

//        $sessionID = $this->createUploadSessionId($token,$account_id);
//        $file = $this->file_upload($token,$account_id,$sessionID,$dir_apk);
//        $update_app = $this->contentUpdate($token,$account_id,$contentId,$file->fileKey);
        $submit_app = $this->contentSubmit($token,$account_id,$contentId);

        dd($submit_app);

//        dd($file);



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

    function createUploadSessionId($token,$account_id){
        $endpoint = "https://devapi.samsungapps.com/seller/createUploadSessionId";
        $Headers = [
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=> $account_id,
        ];
        try {
            $response = Http::withHeaders($Headers)->post( $endpoint);
            if ($response->successful()){
                $result = $response->json();
                $return = $result['sessionId'];
                return $return ;
            }
        }catch (\Exception $exception) {
            Log::error('Message: Upload Session_Id ---' . $exception->getMessage() . $exception->getLine());
        }
    }

    function file_upload($token, $account_id, $sessionID,$dir){
        try {
            $cFile = curl_file_create($dir);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://seller.samsungapps.com/galaxyapi/fileUpload');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type' => 'multipart/form-data',
                'Accept'=> 'application/json',
                'Authorization' => 'Bearer '.$token,
                'service-account-id' => $account_id,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'file' => $cFile,
                'sessionId' => $sessionID,
            ]);


            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch);
            return $result;
        }catch (\Exception $exception) {
            Log::error('Message: file_upload' . $exception->getMessage() . $exception->getLine());
        }
    }

    function contentUpdate($token,$account_id,$contentId,$fileKey){
            $dataArr = [
                "contentId"=> $contentId,
                "defaultLanguageCode" => "ENG",
                "paid" => "N",
                "binaryList" =>[
                    [
                        "gms" => "Y",
                        "filekey"=> $fileKey
                    ],
                ]
            ];

            $endpoint = "https://devapi.samsungapps.com/seller/contentUpdate";
            $endpoint_submit = "https://devapi.samsungapps.com/seller/contentSubmit";
            $Headers = [
                'Content-Type'=> 'application/json',
                'Authorization'=> 'Bearer ' . $token,
                'service-account-id'=>$account_id,
            ];
        try {
            $response = Http::withHeaders($Headers)->post($endpoint, $dataArr);
            $response_submit = Http::withHeaders($Headers)->post($endpoint_submit, $dataArr);

            $contentInfo = $this->contentInfo($token,$account_id,$contentId);
            dd($response_submit->json(),$response->json(),$contentInfo);
            if ($response->successful()){
                $result = $response->json();
                $return = $result['contentStatus'];
                return $return ;
            }
        }catch (\Exception $exception) {
            Log::error('Message: contentUpdate -- ' . $exception->getMessage() . $exception->getLine());
        }

    }

    function contentSubmit($token,$account_id,$contentId){


//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://devapi.samsungapps.com/seller/contentSubmit');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($ch, CURLOPT_HTTPHEADER, [
//            'Content-Type' => 'application/json',
//            'authorization ' => 'Bearer  '.$token,
//            'service-account-id' => $account_id,
//        ]);
////        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"contentId": "000006482916"}');
//
//        $response = curl_exec($ch);
//
//
//
//        $result = json_decode($response);
//
//
//
//        dd($response, $result,$contentId,3423);
//
//        curl_close($ch);



        $dataArr = [
            'contentId'=>$contentId
        ];

        $endpoint = "https://devapi.samsungapps.com/seller/contentSubmit";
        $headers = [
            'Content-Type'=> 'application/json',
            'Authorization'=> 'Bearer ' . $token,
            'service-account-id'=> $account_id,

        ];
        $contentId_detail = $this->contentInfo($token,$account_id,$contentId);

//        try {
            $response = Http::withHeaders($headers)->post($endpoint, $dataArr);
            dd($contentId_detail,$response,$response->json());
            if ($response->successful()){
                $result = $response->json();
                dd($result);
                $return = $result['contentStatus'];
                return $return ;
            }
//        }catch (\Exception $exception) {
//            Log::error('Message: contentSubmit -- ' . $exception->getMessage() . $exception->getLine());
//        }
    }

    public function picture($rand,$url){
        $url = str_replace('&','/',$url);
        $result =  response()->file(public_path('/storage/projects/').$url);
        return ($result);

    }

    /**
     * @throws \Exception
     */
    public function amazon(){
        $this->getToken_amazon();




        $Client_ID = 'amzn1.application-oa2-client.0b087c09c17542c98c82e5dc9a86a5f7';
        $Client_Secret = 'fd94434046e7531375dcdd1100570e7357c236636720f17c38cccd6d66021160';
        $awsAccessKeyId = 'AKIAR5KHVWR24J24JW3G';
        $awsSecretAccessKey = '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';
        $lwaRefreshToken = 'Atzr|IwEBIF5mgp8HU-aN8u4hNUSorORLIP8KwO25aReFqYkBkZj7qJEOD61itKVHWsRAAJ9XPvYgU5-Sn_4_gunFMj9_QGoUkBIp4M0xpPrastN_BqC4vO9UZtggietx9mkupLVP11oYO1O0JfLPwhcXGehXf5jxnILAhGq_iJ5ovxtypSXbNlfwm1mFpTiBbhCcm2nIh1uG5ja33INMUF8BWjRtj2Oe13JrJ7h0TRPhuUJ492O06uO3xtvmvf1lddWfgqxDjZmD46FeE8ZYs7IWTFVO_Wnde86qDJuWKXpnBXh_8bp1ROfVyvltZzBjKgxkdt-Ba5872DXHfjqgqPiAZ2fmeDefALWUAQ3qjK7Z7PnyXk8xtWFr1jsa_L1eq1h2uH-ORZpisepGB7SmV13U1T0qLcSEONxQkof5eGDsH80aTPpW1w';


//        $this->getToken_amazon($Client_ID,$Client_Secret);

//        $config = new Configuration([
//            "lwaClientId" => $Client_ID,
//            "lwaClientSecret" => $Client_Secret,
//            "lwaRefreshToken" => "",
//            "awsAccessKeyId" => $awsAccessKeyId,
//            "awsSecretAccessKey" => $awsSecretAccessKey,
////            // If you're not working in the North American marketplace, change
////            // this to another endpoint from lib/Endpoint.php
//            "endpoint" => Endpoint::NA,
//            "accessToken" => null,
//        ]);
//        $apiInstance = new AuthorizationV1Api($config);
//        $apiInstance = new \Authorization($config);
//
//        dd($apiInstance->getAwsCredentials());
//        $selling_partner_id = 'selling_partner_id_example'; // string | The seller ID of the seller for whom you are requesting Selling Partner API authorization. This must be the seller ID of the seller who authorized your application on the Marketplace Appstore.
//        $developer_id = 'developer_id_example'; // string | Your developer ID. This must be one of the developer ID values that you provided when you registered your application in Developer Central.
//        $mws_auth_token = 'mws_auth_token_example'; // string | The MWS Auth Token that was generated when the seller authorized your application on the Marketplace Appstore.

//        dd($config);
//        dd($config);
//        $api = new SellersApi($config);
//        $apiInstance = new AuthorizationV1Api($config);
//        dd(1);

        $config = ([
            "lwaClientId" => $Client_ID,
            "lwaClientSecret" => $Client_Secret,
            "lwaRefreshToken" => "",
            "awsAccessKeyId" => $awsAccessKeyId,
            "awsSecretAccessKey" => $awsSecretAccessKey,
//            // If you're not working in the North American marketplace, change
//            // this to another endpoint from lib/Endpoint.php
            "endpoint" => Endpoint::NA,
            "accessToken" => null,
        ]);
        $apiInstance = new Authentication ($config);

//        dd($apiInstance);
        $body = new \SellingPartnerApi\Model\TokensV20210301\CreateRestrictedDataTokenRequest(); // \SellingPartnerApi\Model\TokensV20210301\CreateRestrictedDataTokenRequest | The restricted data token request details.
//        dd($body);
        $result = $apiInstance->getGrantlessAwsCredentials();

        dd($result);
        try {
//            $result = $api->getMarketplaceParticipations();
//            dd($result);
        } catch (ApiException $e) {
            echo 'Exception when calling SellersApi->getMarketplaceParticipations: ', $e->getMessage(), PHP_EOL;
        }
    }

//    function getToken_amazon($Client_ID,$Client_Secret){
//        $client = new \GuzzleHttp\Client();
//        $res = $client->post("https://api.amazon.com/auth/o2/token", [
//            \GuzzleHttp\RequestOptions::JSON => [
//                "grant_type" => "authorization_code",
//                "code" => 'profile',
//                "client_id" => $Client_ID,
//                "client_secret" => $Client_Secret
//            ]
//        ]);
//
//        dd($res);
//    }

    function getToken_amazon(){

        $Client_ID = 'amzn1.application-oa2-client.0b087c09c17542c98c82e5dc9a86a5f7';
        $Client_Secret = '9431068a70c8ae6f99b1faa3ebfe6af80466d1b484296558b1c71af36c663bd4';
//        $redirect_uri = 'https://aio.vietmmo.net/api/redirect_uri';
        $redirect_uri = $_SERVER['APP_URL'];
        $redirect_uri .= "/api/redirect_uri";
        $Headers = [
//            'Authorization'=> 'Bearer ' . $JWT,
            'Content-Type'=>'application/x-www-form-urlencoded;charset=UTF-8',
//            'content-type'=>'application/json',
        ];

        $dataArr = array(
            'grant_type' => 'authorization_code',
            'client_id' => $Client_ID,
            'client_secret' => $Client_Secret,
            'scope' => 'appstore::apps:readwrite',
//            'scope' => 'profile',
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
        );
        $url = 'https://www.amazon.com/ap/oa';
        $endpoint = "https://www.amazon.com/ap/oa?client_id=$Client_ID&scope=profile&response_type=code&redirect_uri=$redirect_uri";
        echo '<META http-equiv="refresh" content="0;URL=' . $endpoint . '">';
        dd($endpoint);






        try {
            $response = Http::withHeaders($Headers)->get( $endpoint);
            dd($response,$response->json());
            if ($response->successful()){
                $result = $response->json();
                $accessToken = $result['createdItem']['accessToken'];
                return $accessToken;
            }
        }catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '--- Token: ' . $exception->getLine());
        }
    }

    public function redirect_uri(Request $request){
//        dd($request->all());
        $Client_ID = 'amzn1.application-oa2-client.0b087c09c17542c98c82e5dc9a86a5f7';
        $Client_Secret = '9431068a70c8ae6f99b1faa3ebfe6af80466d1b484296558b1c71af36c663bd4';
        $code = $request->code;
        $url = 'https://api.amazon.com/auth/o2/token';
        $redirect_uri = $_SERVER['APP_URL'];
        $redirect_uri .= "/api/redirect_uri";
        $dataArr = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $Client_ID,
            'client_secret' => $Client_Secret,
            'redirect_uri' => $redirect_uri,
        );

        $Headers = [
            'Content-Type'=>'application/json',
        ];
        $response = Http::withHeaders($Headers)->post( $url,$dataArr);

        dd($response, $response->json());

    }

}
