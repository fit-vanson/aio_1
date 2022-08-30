<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailParent extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table= 'ngocphandang_parent';
    protected $guarded = [];
}
