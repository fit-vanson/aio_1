<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markets extends Model
{
    use HasFactory;

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    public function templates()
    {
        return $this->hasManyJson(Template::class, 'category[]->market_id');
    }
}
