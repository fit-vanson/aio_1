<?php

namespace App\Http\Controllers;

use App\Models\ICCID;
use App\Models\Imei;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class ImeiController extends Controller
{
    public function index()
    {
        $brand = Imei::select('brand')->distinct('brand')->get();
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Index"]
        ];
        return view('imei.index', [
            'breadcrumbs' => $breadcrumbs,
            'brand' =>$brand,
            ]);
    }

    public function getBrand(Request $request){
        $brand = $request->brand;
        if($brand){
            $model = Imei::where('brand',$brand)->get();
            return response([
                'data'=>$model
            ]);
        }
    }

    public function create(Request $request){
        $data = new Imei();
        $data->imei_code = substr($request->imei,0,10);
        $data->brand = $request->brand;
        $data->model= $request->model;
        $data->save();
    }
    public function import(){
        ini_set('max_execution_time',30000);
        $file = file('C:\Users\Administrator\Desktop\data\imeidb.csv');
        $parts = (array_chunk($file,1000));
        foreach ($parts as $part){
            $items = array_map('str_getcsv',$part);
            foreach ($items as $item){
                Imei::updateOrCreate(
                    [
                        'tac_code' => $item[0]
                    ],
                    [
                        'brand' => $item[1],
                        'model' => $item[2],
                    ]
                ) ;
            }
        }
    }

    public function gen_imei($tac,$show=1){

        if(isset($_GET['show'])){
            $show = $_GET['show'];
        }
        if(isset($_GET['tac_code'])) {
            $tac = $_GET['tac_code'];
        }
        $TAC = [];
        for ($i=0; $i<$show;$i++){
            $tac = substr($tac,0,8);
            while (strlen($tac) < 14){
                $tac .= (string)rand(0,9);
            }
            $tac .= $this->calc_check_digit($tac);
            $TAC[] = $tac;
        }
        return response()->json(['data'=>$TAC]);
    }
    public function calc_check_digit($number,$alphabet='0123456789'){
        $check_digit = $this->checksum($number.$alphabet[0]);
    return $alphabet[-$check_digit];
    }
    public function checksum($number,$alphabet='0123456789'){
        $n = strlen($alphabet);
        $number = array_reverse(array_map('intval', str_split($number)));
        $b = 0;
        $sum = 0;
        foreach ($number as $key=>$num){
            if($key%2 !=1){
                $sum+= $num;
            }else{
                $a = $num;
                $div = (int)floor($a*2/$n);
                $mov = $a*2%$n;
                $b += $div+$mov;
            }
        }
        $total = $sum+$b;
        return $total%$n;
    }

    public function show_imei(Request $request){
       if($request->brand){
           $model = Imei::inRandomOrder()->where('brand','like', '%' . $request->brand . '%')->limit(5)->get();
           if(count($model)>0){
               foreach ($model as $item){
                   if(isset($request->model)){
                       $tac = Imei::inRandomOrder()->where('brand','like', '%' . $request->brand . '%')->where('model','like', '%' . $request->model . '%')->first();
                       if(isset($tac)){
                           $gen_imei =  $this->gen_imei($tac->tac_code,1);
                           return $tac->brand . ' | ' .$tac->model. ' | '.$gen_imei->getData()->data[0];
                       }else{
                           return 'Chưa có dữ liệu';
                       }
                   }else{
                       echo '<a href="?brand='.$item->brand.'&model='.$item->model.'">'.$item->model.'</a><br />';
                   }
               }
           }else{
               return 'Chưa có dữ liệu';
           }
       }elseif ($request->tac_code){
           $tac_code = $request->tac_code;
           $a = $this->gen_imei($tac_code,1);
           echo $a->getData()->data[0];
       }
       else{
           $brand = Imei::inRandomOrder()->select('brand')->distinct('brand')->limit(20)->get();
           foreach ($brand as $item){
               echo '<a href="?brand='.$item->brand.'">'.$item->brand.'</a><br />';
           }
       }
    }



    public function index_iccid()
    {
        $country = ICCID::select('country','countrycode')->distinct('country')->get();
        $breadcrumbs = [
            ['link' => "home", 'name' => "Home"], ['name' => "Index"]
        ];
        return view('iccid.index', [
            'breadcrumbs' => $breadcrumbs,
            'country' =>$country,
        ]);
    }

    public function getCountry(Request $request){
        $country= $request->country;
        if($country){
            $network = ICCID::where('Country',$country)->get();
            return response([
                'data'=>$network
            ]);
        }
    }

    public function gen_iccid($iccid='89',$show=1){
        if(isset($_GET['show'])){
            $show = $_GET['show'];
        }
        if(isset($_GET['iccid'])) {
            $iccid .= $_GET['iccid'];

        }
        $ICCID = [];
        for ($i=0; $i<$show;$i++){
            $iccid = substr($iccid,0,17);
            while (strlen($iccid) < 19){
                $iccid .= (string)rand(0,9);
            }
            $iccid .= $this->calc_check_digit($iccid);
            $ICCID[] = $iccid;
        }
        return response()->json(['data'=>$ICCID]);
    }

    public function show_iccid(Request $request){
        if($request->country){
            $network = ICCID::where('country','like', '%' . $request->country . '%')->get();
            if(count($network)>0){
                foreach ($network as $item){
                    if(isset($request->network)){
                        $iccid = ICCID::inRandomOrder()->where('country','like', '%' . $request->country . '%')->where('network','like', '%' . $request->network . '%')->first();
                        if(isset($iccid)){
                            $id = '89'.$iccid->countrycode.$iccid->mnc;
                            $gen_iccid =  $this->gen_iccid($id,1);
                            return $iccid->country . ' | ' .$iccid->network. ' | '.$gen_iccid->getData()->data[0];
                        }else{
                            return 'Chưa có dữ liệu';
                        }
                    }else{
                        echo '<a href="?country='.$item->country.'&network='.$item->network.'">'.$item->network.' - '.$item->mnc.'</a><br />';
                    }
                }
            }else{
                return 'Chưa có dữ liệu';
            }
        }elseif ($request->iccid){
            $iccid = $request->iccid;
            $a = $this->gen_iccid($iccid,1);
            echo $a->getData()->data[0];
        }
        else{
            $country = ICCID::inRandomOrder()->select('country')->distinct('country')->limit(20)->get();
            foreach ($country as $item){
                echo '<a href="?country='.$item->country.'">'.$item->country.'</a><br />';
            }
        }
    }
}
