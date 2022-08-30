<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dev_Samsung extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_dev_samsung';
    protected $guarded = [];
    public function ga(){
        return $this->belongsTo(Ga::class,'samsung_ga_name');
    }

    public function gadev(){
        return $this->belongsTo(Ga_dev::class,'samsung_email');
    }
    public function project(){
        return $this->hasMany(ProjectModel::class,'Chplay_buildinfo_store_name_x');
    }

}

