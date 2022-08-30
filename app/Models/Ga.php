<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ga extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ngocphandang_ga';
    protected $fillable = [
        'ga_name','gmail_gadev_chinh','gmail_gadev_phu_1','gmail_gadev_phu_2','info_phone','info_andress','payment','app_ads','note','status'
    ];

    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_chinh');
    }
    public function gadev1(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_phu_1');
    }
    public function gadev2(){
        return $this->belongsTo(Ga_dev::class,'gmail_gadev_phu_2');
    }

    public function dev(){
        return $this->hasMany(Dev::class,'id_ga');
    }
    public function dev_amazon(){
        return $this->hasMany(Dev_Amazon::class,'amazon_ga_name');
    }

    public function dev_samsung(){
        return $this->hasMany(Dev_Samsung::class,'samsung_ga_name');
    }
    public function dev_xiaomi(){
        return $this->hasMany(Dev_Xiaomi::class,'xiaomi_ga_name');
    }
    public function dev_oppo(){
        return $this->hasMany(Dev_Oppo::class,'oppo_ga_name');
    }
    public function dev_vivo(){
        return $this->hasMany(Dev_Vivo::class,'vivo_ga_name');
    }
    public function dev_huawei(){
        return $this->hasMany(Dev_Huawei::class,'huawei_ga_name');
    }




}
