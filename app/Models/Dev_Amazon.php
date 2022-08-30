<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev_Amazon extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_dev_amazon';
    protected $guarded = [];
    public function ga(){
        return $this->belongsTo(Ga::class,'amazon_ga_name');
    }

    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'amazon_email');
    }
    public function project(){
        return $this->hasMany(ProjectModel::class,'Amazon_buildinfo_store_name_x');
    }

}

