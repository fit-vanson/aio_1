<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    public $timestamps = false;
    protected $table = 'ngocphandang_template';


    protected $guarded = [];

    protected $casts = [
        'category' => 'json',
    ];

    public function markets()
    {
        return $this->belongsToJson(Markets::class, 'category[]->market_id');
//        return $this->belongsToJson(Markets::class, 'category[]');
    }

    public function apktool()
    {
        return $this->belongsTo(ApkTools::class, 'apktool');
    }

    public function project(){
        return $this->hasMany(Project::class,'template');
    }

}
