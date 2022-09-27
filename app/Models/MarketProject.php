<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketProject extends  \Illuminate\Database\Eloquent\Relations\Pivot
{
    use HasFactory;
    use EagerLoadPivotTrait;
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

    protected $table = 'market_projects';

    public function dev()
    {
        return $this->belongsTo(Market_dev::class,'dev_id');
    }
}
