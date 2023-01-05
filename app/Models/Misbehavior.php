<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Misbehavior extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'teacher_id',
        'report_id',
        'behavior',
        'name',

    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
