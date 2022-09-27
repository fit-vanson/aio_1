<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class Markets extends Model
{
    use HasFactory;
    use EagerLoadPivotTrait;


    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    public function templates()
    {
        return $this->hasManyJson(Template::class, 'category[]->market_id');
    }
    public function projects(){
        return $this->belongsToMany(Project::class,MarketProject::class,'market_id','project_id')
            ->withPivot('dev_id','appID','app_name_x','package','ads','app_link','policy_link','video_link','sdk','keystore','status_app','status_upload','time_upload');
    }



}
