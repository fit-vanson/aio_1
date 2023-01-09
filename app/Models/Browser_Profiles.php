<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Browser_Profiles extends Model
{
    use HasFactory;
    protected $table = 'browser_profiles';

    public $timestamps = false;

    protected $guarded = [];

    public function ftp_account()
    {
        return $this->belongsTo(FtpAccount::class, 'ftp_id');
    }
}
