<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCompany extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_profiles_companies';
    protected $guarded =[];

    public function profile()
    {
        return $this->belongsTo(ProfileV2::class,'ma_profile');
    }
}
