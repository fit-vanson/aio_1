<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileV2 extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_profiles_v2';
//    protected $guarded =['profile_name','profile_ho_va_ten','profile_cccd','profile_ngay_cap','profile_ngay_sinh','profile_sex','profile_add','profile_cong_ty'];
    protected $guarded =[];

    public function company()
    {
        return $this->hasMany(ProfileCompany::class,'ma_profile');
    }
}
