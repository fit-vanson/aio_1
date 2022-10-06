<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ga_dev extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'gmail_gadev';
//    protected $fillable = [
//        'gmail','mailrecovery','vpn_iplogin'
//    ];
    protected $guarded = [];

    public function devs()
    {
        return $this->hasMany(Dev::class,'mail_id_1');
    }
}
