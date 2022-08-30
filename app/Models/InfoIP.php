<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoIP extends Model
{
    use HasFactory;
    protected $table= 'tbl_ip';
    protected $guarded = [];
}
