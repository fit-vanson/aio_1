<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev extends Model
{
    use HasFactory;
//    use \Awobaz\Compoships\Compoships;

    public $timestamps = false;
//    protected $table = 'ngocphandang_dev';
    protected $table = 'market_devs';
    protected $guarded=[];

    public function ga(){
        return $this->belongsTo(Ga::class,'ga_id');
    }
    public function markets(){
        return $this->belongsTo(Markets::class,'market_id');
    }
    public function profile(){
        return $this->belongsTo(ProfileV2::class,'profile_id');
    }

    public function gmail_dev1(){
        return $this->belongsTo(Ga_dev::class,'mail_id_1');
    }
    public function gmail_dev2(){
        return $this->belongsTo(Ga_dev::class,'mail_id_2');
    }

}

