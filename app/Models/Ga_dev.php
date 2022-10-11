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
//    protected $fillable = [
//        'gmail','mailrecovery','vpn_iplogin'
//    ];
    protected $guarded = [];


    public function devs_1()
    {
        return $this->hasMany(Dev::class,'mail_id_1');
    }
    public function devs_2()
    {
        return $this->hasMany(Dev::class,'mail_id_2');
    }

}
