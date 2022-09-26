<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketProject extends Model
{
    use HasFactory;
    protected $fillable = [
        'market_id',
        'project_id',
        'dev_id',
        'app_name_x',
        'appID',
        'package',
        'ads',
        'app_link',
        'policy_link',
        'sdk',
        'keystore',
        'status_app',
        'status_app',
    ];
    public $timestamps= false;

    public function dev(){
        return $this->belongsTo(Market_dev::class,'dev_id');
    }
}
