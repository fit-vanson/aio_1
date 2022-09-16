<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarketDevResource;
use App\Models\Market_dev;
use Illuminate\Http\Request;

class MarketDevController extends Controller
{
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
}
