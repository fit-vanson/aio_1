<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApkUploadConvert extends Model
{
    use HasFactory;
    protected $table ='apkupload_convert';
    public $timestamps = false;
    protected $guarded = [];
}
