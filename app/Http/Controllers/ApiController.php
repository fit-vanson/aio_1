<?php

namespace App\Http\Controllers;

use App\Http\Resources\DaResource;
use App\Http\Resources\GaResource;
use App\Http\Resources\KeystoresResource;
use App\Http\Resources\MarketDevResource;
use App\Http\Resources\TemplateResource;
use App\Models\Da;
use App\Models\Ga;
use App\Models\Keystore;
use App\Models\Market_dev;
use App\Models\Template;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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
            ->whereIn('startus',  [0,1])
            ->get();
        $result = MarketDevResource::collection($dev);
        return response()->json($result);
    }


    public function getKeystore(){
        $searchValue = \request()->q;

        $dev = Keystore::latest('id')
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

}
