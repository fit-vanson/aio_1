<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exiftool extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($file) { // before delete() method call this
            $path = storage_path('app/public/exiftool/'.$file->name.'/');
            Exiftool::deleteDirectory($path);
        });
    }

    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!Exiftool::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}
