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
        'outstanding_student',
        'need_improvement_student',
        'creator',
        'approver',
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

    public function scopeFilter(Builder $query, array $filters): void
    {
        if (!empty($filters["search"])) {
            $query->where(function ($qr) use ($filters) {
                $qr->Where("topic", "like", "%$filters[search]%");
            });
        }
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
