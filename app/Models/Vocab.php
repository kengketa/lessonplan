<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocab extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'grade_id',
        'subject_id',
        'subject_name',
        'vocab_th',
        'vocab_en',
        'academic_year',
        'semester'
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
