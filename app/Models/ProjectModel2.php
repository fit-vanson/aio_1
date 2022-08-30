<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel2 extends Model
{
    use HasFactory;
    protected $table = 'ngocphandang_project2';
    protected $primaryKey = 'projectid';
    protected $guarded =[];
}
