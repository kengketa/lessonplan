<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentGrade extends Model
{
    protected $table = 'grade_subject';
    protected $fillable = [
        'grade_id',
        'subject_id',
        'teacher_id',
        'academic_year',
        'semester',
    ];

    protected static function booted()
    {
        static::addGlobalScope('currentGrade', function ($query) {
            $query->where([
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester(),
            ]);
        });
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
