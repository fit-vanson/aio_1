<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev_Vivo extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_dev_vivo';
    protected $guarded = [];

    public function ga(){
        return $this->belongsTo(Ga::class,'vivo_ga_name');
    }

    public function ga_dev(){
        return $this->belongsTo(Ga_dev::class,'vivo_email');
    }

    public function project(){
        return $this->hasMany(ProjectModel::class,'Vivo_buildinfo_store_name_x');
    }
}

