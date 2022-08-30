<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Keystore extends Model
{
    use HasFactory;
    protected $table= 'ngocphandang_keystores';
    protected $guarded = [];
    public function project(){
        return $this->hasMany(ProjectModel::class,'buildinfo_keystore','name_keystore');
    }
    public function project_chplay(){
        return $this->hasMany(ProjectModel::class,'Chplay_keystore_profile','name_keystore');
    }
    public function project_amazon(){
        return $this->hasMany(ProjectModel::class,'Amazon_keystore_profile','name_keystore');
    }
    public function project_samsung(){
        return $this->hasMany(ProjectModel::class,'Samsung_keystore_profile','name_keystore');
    }
    public function project_xiaomi(){
        return $this->hasMany(ProjectModel::class,'Xiaomi_keystore_profile','name_keystore');
    }
    public function project_oppo(){
        return $this->hasMany(ProjectModel::class,'Oppo_keystore_profile','name_keystore');
    }
    public function project_vivo(){
        return $this->hasMany(ProjectModel::class,'Vivo_keystore_profile','name_keystore');
    }
    public function project_huawei(){
        return $this->hasMany(ProjectModel::class,'Huawei_keystore_profile','name_keystore');
    }
}





