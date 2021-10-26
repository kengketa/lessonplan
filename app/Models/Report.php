<?php

namespace App\Models;

use App\Presenters\ReportPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = ReportPresenter::class;

    public const TOPIC_PHONIC = 1;
    public const TOPIC_LEARNING_AREA = 2;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grade_id',
        'academic_year',
        'semester',
        'week_number',
        'lesson_number',
        'date',
        'plans',
        'subject',
        'outcome',
        'teaching_materials',
        'activities',
        'outstanding_students',
        'need_improvement_students',
        'creator_id',
        'approver_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'plans' => 'json'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */

    public function scopeFilter(Builder $query, array|null $filters): void
    {
        if (!isset($filters)) {
            $query->where('academic_year', getCurrentAcademicYear())->where('semester', getCurrentSemester());
            return;
        }
        if ($filters['academic_year'] == 0) {
            $query->where('academic_year', getCurrentAcademicYear());
        }
        if ($filters['semester'] == 0) {
            $query->where('semester', getCurrentSemester());
        }
        if ($filters['academic_year'] != 0) {
            $query->where('academic_year', $filters['academic_year']);
        }
        if ($filters['semester'] != 0) {
            $query->where('semester', $filters['semester']);
        }
        if ($filters['grade'] != 0) {
            $query->where('grade_id', $filters['grade']);
        }
        if ($filters['subject'] != 0) {
            $query->where('subject', $filters['subject']);
        }
        if ($filters['teacher'] != 0) {
            $query->where('creator_id', $filters['teacher']);
        }
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id')->withTrashed();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id')->withTrashed();
    }
}
