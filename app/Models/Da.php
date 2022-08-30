<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Da extends Model
{
    use HasFactory;

    protected $table = 'ngocphandang_da';
    protected $fillable = ['ma_da'];

    public function project(){
        return $this->hasMany(ProjectModel::class,'ma_da');
    }

}
