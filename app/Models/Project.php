<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;


class Project extends Model
{
    use HasFactory;
    use EagerLoadPivotTrait;
    protected $table = 'ngocphandang_project';
    protected $primaryKey = 'projectid';
    protected $guarded =[];


    public function markets(){
        return $this->belongsToMany(Markets::class,MarketProject::class,'project_id','market_id')
            ->withPivot('id','dev_id','appID','app_name_x','package','ads','app_link','policy_link','video_link','sdk','keystore','status_app','status_upload','time_upload');
    }

    public function dev()
    {
        return $this->hasManyThrough(
            Market_dev::class, MarketProject::class,
            'dev_id', 'id', 'project_id'
        );
    }



    public function lang(){
        return $this->belongsToMany(Language::class,ProjectHasLang::class,'project_id','lang_id')
            ->withPivot('title','description','summary','banner','pr1','pr2','pr3','pr4','pr5','pr6','pr7','pr8','video')
            ->withTimestamps();
    }

    public function da(){
        return $this->belongsTo(Da::class,'ma_da');
    }

    public function ma_template(){
        return $this->belongsTo(Template::class,'template');
    }
//    public function matemplate(){
//        return $this->belongsTo(Template::class,'template');
//    }





}
