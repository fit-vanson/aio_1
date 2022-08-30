<?php

namespace App\Http\Controllers;

use Adrianorosa\GeoLocation\GeoLocation;
use App\Models\InfoIP;
use Illuminate\Http\Request;

class ipInfoController extends Controller
{
    public function index()
    {
        return view('ipinfo.index');
    }

    public function getIndex(Request $request){
        $draw = $request->get('draw');
        $search_arr = $request->get('search');
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = $totalRecordswithFilter = 1;
        $records =[];
        if($searchValue != null){

                $details = GeoLocation::lookup($searchValue);
                $records['ip'] = $details->getIp();
                $records['city'] = $details->getCity();
                $records['region'] = $details->getRegion();
                $records['country'] = $details->getCountry();
                $records['countryCode'] = $details->getCountryCode();
                $records['latitude'] = $details->getLatitude();
                $records['longitude'] = $details->getLongitude();
                $records['timezone'] = $this->get_nearest_timezone($records['latitude'], $records['longitude'], $records['countryCode']);
                InfoIP::updateOrCreate(
                    [
                        'ip' => substr($details->getIp(),0,strrpos($details->getIp(),'.'))
                    ],
                    [
                        'city' => $details->getCity(),
                        'region' => $details->getRegion(),
                        'country' => $details->getCountry(),
                        'countryCode' => $details->getCountryCode(),
                        'latitude' => $details->getLatitude(),
                        'longitude' => $details->getLongitude(),
                        'timezone' => $records['timezone']
                    ]
                );

        }
        $link = 'https://www.google.com/maps/place/'.$records['latitude'].','.$records['longitude'];

        $data_arr = array();
            $data_arr[] = array(
                "ip" =>  $records['ip'],
                "city" =>  $records['city'],
                "region" =>  $records['region'],
                "country" =>  $records['country'],
                "countryCode" =>  $records['countryCode'],
                "latitude" =>  $records['latitude'],
                "longitude" =>  $records['longitude'],
                "timezone" =>  $records['timezone'],
                "link" => '<a target="_blank" href="'.$link.'">Link GG</a>',
            );
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );
        echo json_encode($response);
    }

    public function IP2location(Request $request){
       if($request->ip){
           $ip = $request->ip;

           if (filter_var($ip, FILTER_VALIDATE_IP)) {
               $pos = strrpos($ip,'.');
               $ipC = substr($ip,0,$pos);
               $check = InfoIP::where('ip',$ipC)->first();
               if(isset($check)){
                   $records['ip'] = $check->ip;
                   $records['city'] = $check->city;
                   $records['region'] = $check->region;
                   $records['country'] = $check->country;
                   $records['countryCode'] = $check->countryCode;
                   $records['latitude'] = $check->latitude;
                   $records['longitude'] = $check->longitude;
                   $records['timezone'] = $check->timezone;
                   echo $records['ip'] . ' | ' .$records['city'] . ' | ' .$records['region'] . ' | ' .$records['country'] . ' | ' .$records['countryCode'] . ' | ' .$records['latitude'] . ' | ' .$records['longitude']. ' | ' .$records['timezone'];
               }else{
                   $details = GeoLocation::lookup($ip);
                   $records['ip'] = $details->getIp();
                   $records['city'] = $details->getCity();
                   $records['region'] = $details->getRegion();
                   $records['country'] = $details->getCountry();
                   $records['countryCode'] = $details->getCountryCode();
                   $records['latitude'] = $details->getLatitude();
                   $records['longitude'] = $details->getLongitude();
                   $records['timezone'] = $this->get_nearest_timezone($records['latitude'], $records['longitude'], $records['countryCode']);

                   InfoIP::updateOrCreate(
                       [
                           'ip' => substr($details->getIp(),0,strrpos($details->getIp(),'.'))
                       ],
                       [
                           'city' => $details->getCity(),
                           'region' => $details->getRegion(),
                           'country' => $details->getCountry(),
                           'countryCode' => $details->getCountryCode(),
                           'latitude' => $details->getLatitude(),
                           'longitude' => $details->getLongitude(),
                           'timezone' => $records['timezone']
                       ]
                   );
                   echo $records['ip'] . ' | ' .$records['city'] . ' | ' .$records['region'] . ' | ' .$records['country'] . ' | ' .$records['countryCode'] . ' | ' .$records['latitude'] . ' | ' .$records['longitude']. ' | ' .$records['timezone'];
               }
           } else {
               echo("$ip is not a valid IP address");
           }
       }else{
           echo("/IP2location?ip=x.x.x.x");
       }
    }


    function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
        $timezone_ids = ($country_code) ? \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country_code)
            : \DateTimeZone::listIdentifiers();

        if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

            $time_zone = '';
            $tz_distance = 0;

            //only one identifier?
            if (count($timezone_ids) == 1) {
                $time_zone = $timezone_ids[0];
            } else {

                foreach($timezone_ids as $timezone_id) {
                    $timezone = new \DateTimeZone($timezone_id);
                    $location = $timezone->getLocation();
                    $tz_lat   = $location['latitude'];
                    $tz_long  = $location['longitude'];

                    $theta    = $cur_long - $tz_long;
                    $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                        + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                    $distance = acos($distance);
                    $distance = abs(rad2deg($distance));
                    // echo '<br />'.$timezone_id.' '.$distance;

                    if (!$time_zone || $tz_distance > $distance) {
                        $time_zone   = $timezone_id;
                        $tz_distance = $distance;
                    }

                }
            }
            return  $time_zone;
        }
        return 'unknown';
    }




}
