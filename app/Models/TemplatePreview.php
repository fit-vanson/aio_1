<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePreview extends Model
{
    use HasFactory;

    public function sum_script(){

        dd(TemplatePreview::select('count(*) as allcount')->count());

    }

    public function CategoryTemplate(){
        return $this->belongsTo(CategoryTemplateFrame::class,'tp_category');
    }
}
