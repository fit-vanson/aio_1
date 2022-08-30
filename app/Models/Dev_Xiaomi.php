<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev_Xiaomi extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_dev_xiaomi';
    protected $guarded = [];

    public function ga(){
        return $this->belongsTo(Ga::class,'xiaomi_ga_name');
    }

    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'xiaomi_email');
    }
    public function project(){
        return $this->hasMany(ProjectModel::class,'Xiaomi_buildinfo_store_name_x');
    }

}

