<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTemplate extends Model
{
    use HasFactory;
    protected $fillable =['parent_id'];

    public function parent()
    {
        return $this->belongsTo(CategoryTemplate::class, 'category_template_parent');
    }

    public function getParentsNames() {
        if($this->parent) {
            return $this->parent->getParentsNames(). " > " . $this->name;
        } else {
            return $this->name;
        }
    }
}
