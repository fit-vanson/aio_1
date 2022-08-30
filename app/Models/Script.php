<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_script';
    protected $guarded = [];
    public $timestamps = false;
}
