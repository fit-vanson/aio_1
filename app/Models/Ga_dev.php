<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ga_dev extends Model
{
    use HasFactory;
//    use \Awobaz\Compoships\Compoships;

    public $timestamps = false;
    protected $table = 'gmail_gadev';
    protected $guarded = [];


    public function devs_1()
    {
        return $this->hasMany(Dev::class,'mail_id_1');
    }
    public function devs_2()
    {
        return $this->hasMany(Dev::class,'mail_id_2');
    }
    public function ga()
    {
        return $this->hasOne(Ga::class,'gmail_gadev_chinh');
    }
    public function ga_1()
    {
        return $this->hasOne(Ga::class,'gmail_gadev_phu_1');
    }
    public function ga_2()
    {
        return $this->hasOne(Ga::class,'gmail_gadev_phu_2');
    }

}
