<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Market_dev extends Pivot
{
    use HasFactory;
    use EagerLoadPivotTrait;
    protected $table = 'market_devs';
    public function ga(){
        return $this->belongsTo(Ga::class,'ga_id');
    }
}
