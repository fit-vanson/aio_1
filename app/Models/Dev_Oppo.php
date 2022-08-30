<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev_Oppo extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_dev_oppo';
    protected $guarded = [];
    public function ga(){
        return $this->belongsTo(Ga::class,'oppo_ga_name');
    }
    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'oppo_email');
    }
    public function project(){
        return $this->hasMany(ProjectModel::class,'Oppo_buildinfo_store_name_x');
    }

}

