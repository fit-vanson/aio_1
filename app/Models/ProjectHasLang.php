<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHasLang extends Model
{
    use HasFactory;
    protected $fillable = [
      'project_id',
      'lang_id',
      'content',
      'logo',
      'banner',
      'preview',
      'video',
      'user_design',
      'status',
      'notes',
    ];



    public function project()
    {
        return $this->belongsTo(ProjectModel::class, 'project_id','projectid');
    }

    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_design', 'id');
    }

}
