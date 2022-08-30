<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ngocphandang_dev';
    protected $fillable = [
        'store_name','dev_name','gmail_gadev_chinh','gmail_gadev_phu_1','gmail_gadev_phu_2','info_phone','info_andress','info_url','info_logo','info_banner','info_policydev','info_fanpage','info_web','status'
    ];

    public function ga(){
        return $this->belongsTo(Ga::class,'id_ga');
    }

//    public function gadev(){
//        return $this->belongsTo(Ga_dev::class,['gmail_gadev_chinh','gmail_gadev_phu_1','gmail_gadev_phu_2'],['id','id','id']);
//    }

    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_chinh');
    }
    public function gadev1(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_phu_1');
    }
    public function gadev2(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_phu_2');
    }
    public function project(){
        return $this->hasMany(ProjectModel::class,'Chplay_buildinfo_store_name_x');
    }
}

