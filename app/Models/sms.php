<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sms extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table= 'ngocphandang_sms';
    protected $guarded = [];
}
