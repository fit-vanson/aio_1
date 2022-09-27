<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev extends Model
{
    use HasFactory;

    public $timestamps = false;
//    protected $table = 'ngocphandang_dev';
    protected $table = 'market_devs';
    protected $guarded=[];
//    protected $fillable = [
//        'store_name','dev_name','gmail_gadev_chinh','gmail_gadev_phu_1','gmail_gadev_phu_2','info_phone','info_andress','info_url','info_logo','info_banner','info_policydev','info_fanpage','info_web','status'
//    ];

    public function ga(){
        return $this->belongsTo(Ga::class,'ga_id');
    }
    public function markets(){
        return $this->belongsTo(Markets::class,'market_id');
    }
    public function profile(){
        return $this->belongsTo(ProfileV2::class,'profile_id');
    }


//    public function gadev(){
//        return $this->belongsTo(Ga_dev::class,['gmail_gadev_chinh','gmail_gadev_phu_1','gmail_gadev_phu_2'],['id','id','id']);
//    }

    public function gmail_dev1(){
        return $this->belongsTo(Ga_dev::class,'mail_id_1');
    }
    public function gmail_dev2(){
        return $this->belongsTo(Ga_dev::class,'mail_id_2');
    }

//    public function gadev2(){
//        return $this->belongsTo(Ga_dev::class,'gmail_gadev_phu_2');
//    }
//    public function project(){
//        return $this->hasMany(ProjectModel::class,'Chplay_buildinfo_store_name_x');
//    }
}

