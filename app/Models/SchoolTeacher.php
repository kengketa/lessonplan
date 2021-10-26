<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTeacher extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'teacher_id'];
}
