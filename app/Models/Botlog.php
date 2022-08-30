<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Botlog extends Model
{

    public $timestamps = false;
    protected $table = 'ngocphandang_botlog';
    protected $fillable = [
        'idbot',
        'mess',
        'time',
    ];
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

//    public function botlog(){
//        return $this->belongsTo(Bot::class,'idbot');
//    }
//
    public function bot(){
        return $this->belongsTo(Bot::class,'idbot');
    }
}
