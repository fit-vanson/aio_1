<?php

namespace App\Models;


use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Keystore extends Model
{
    use HasFactory;

    protected $table= 'ngocphandang_keystores';
    protected $guarded = [];
    public $timestamps = false;


    public function project(){
        return $this->belongsToMany(Project::class,MarketProject::class,'project_id','keystore','name_keystore');
//            ->withPivot('dev_id','market_id','appID','app_name_x','package','ads','app_link','policy_link','video_link','sdk','','status_app','status_upload','time_upload');
    }

    public function market_project(){
        return $this->hasMany(MarketProject::class,'keystore','name_keystore');
//            ->withPivot('dev_id','market_id','appID','app_name_x','package','ads','app_link','policy_link','video_link','sdk','','status_app','status_upload','time_upload');
    }

}





