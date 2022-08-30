<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ngocphandang_template';

    protected $fillable = [ 'template',
        'ver_build', 'script_img','script_svg2xml',
        'time_create', 'time_update','time_get','note','link_chplay','category'
    ];

    public function project(){
        return $this->hasMany(ProjectModel::class,'template');
    }
}
