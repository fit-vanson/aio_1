<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cocsim extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table= 'ngocphandang_cocsim';
    protected $guarded = [];

    public function khosim()
    {
        return $this->hasMany(khosim::class, 'cocsim');
    }

    public function hub()
    {
        return $this->hasOne(Hub::class, 'cocsim');
    }

    public static function booted()
    {
        static::deleting(function ($cocsim) {
            $uncategorized = cocsim::firstOrCreate(['cocsim' => 'default']);
            khosim::where('cocsim', $cocsim->id)->update(
                ['cocsim' => $uncategorized->id,'stt'=>0]);
        });
    }

}
