<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateTextPr extends Model
{
    use HasFactory;
    public function CategoryTemplate(){
        return $this->belongsTo(CategoryTemplate::class,'tt_category');
    }
}
