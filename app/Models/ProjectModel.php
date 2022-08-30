<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_project';
    protected $primaryKey = 'projectid';
    protected $guarded =[];
    public function log(){
        return $this->hasOne(log::class,'projectname','projectname');
    }

    public function da(){
        return $this->belongsTo(Da::class,'ma_da');
    }
    public function matemplate(){
        return $this->belongsTo(Template::class,'template');
    }

    public function dev_chplay(){
        return $this->belongsTo(Dev::class,'Chplay_buildinfo_store_name_x');
    }

    public function dev_amazon(){
        return $this->belongsTo(Dev_Amazon::class,'Amazon_buildinfo_store_name_x');
    }
    public function dev_samsung(){
        return $this->belongsTo(Dev_Samsung::class,'Samsung_buildinfo_store_name_x');
    }
    public function dev_xiaomi(){
        return $this->belongsTo(Dev_Xiaomi::class,'Xiaomi_buildinfo_store_name_x');
    }
    public function dev_oppo(){
        return $this->belongsTo(Dev_Oppo::class,'Oppo_buildinfo_store_name_x');
    }
    public function dev_vivo(){
        return $this->belongsTo(Dev_Vivo::class,'Vivo_buildinfo_store_name_x');
    }
    public function dev_huawei(){
        return $this->belongsTo(Dev_Huawei::class,'Huawei_buildinfo_store_name_x');
    }

    public function ga_xiaomi(){
        return $this->belongsToMany(Ga::class,'ngocphandang_dev_xiaomi','id','xiaomi_ga_name');
    }

}
