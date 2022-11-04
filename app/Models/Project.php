<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;


class Project extends Model
{
    use HasFactory;
    use EagerLoadPivotTrait;
    use \Bkwld\Cloner\Cloneable;
    protected $table = 'ngocphandang_project';
    protected $primaryKey = 'projectid';
    protected $guarded =[];
    protected $cloneable_relations = ['markets_copy', 'lang_copy'];


    public function markets(){
        return $this->belongsToMany(Markets::class,MarketProject::class,'project_id','market_id')
            ->withPivot(
//                'id',
                'dev_id',
                'appID',
                'app_name_x',
                'package',
                'ads',
                'app_link',
                'policy_link',
                'video_link',
                'sdk',
                'keystore',
                'status_app',
                'status_upload',
                'use_id_upload',
                'time_upload',
                'bot',
                'bot_time',
                'apk_link',
                'aab_link',
                'bot_installs',
                'bot_numberReviews',
                'bot_numberVoters',
                'bot_score',
                'bot_appVersion'
            );
    }

    public function lang(){
        return $this->belongsToMany(Language::class,ProjectHasLang::class,'project_id','lang_id')
            ->withPivot('title','description','summary','banner','preview','pr1','pr2','pr3','pr4','pr5','pr6','pr7','pr8','video')
            ->withTimestamps();
    }

    public function da(){
        return $this->belongsTo(Da::class,'ma_da');
    }

    public function ma_template(){
        return $this->belongsTo(Template::class,'template');
    }

    public function dev(){
        return $this->belongsToMany(Dev::class,MarketProject::class,'project_id','dev_id');
    }



    public function markets_copy(){
        return $this->belongsToMany(Markets::class,MarketProject::class,'project_id','market_id');
    }

    public function lang_copy(){
        return $this->belongsToMany(Language::class,ProjectHasLang::class,'project_id','lang_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_design');
    }






}
