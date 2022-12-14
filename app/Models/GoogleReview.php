<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReview extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function project_market(){
        return $this->belongsTo(MarketProject::class,'project_market_id');
    }
}
