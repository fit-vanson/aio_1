<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileV2 extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_profiles_v2';

    protected $guarded =[];

    public function company()
    {
        return $this->hasMany(ProfileCompany::class,'ma_profile');
    }
}
