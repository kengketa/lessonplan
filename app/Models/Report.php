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


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grade_id',
        'week_number',
        'lesson_number',
        'date',
        'topic',
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
        'topic' => 'json'
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
                $qr->where("grade_id", "like", "%$filters[search]%")->orWhere("week_number", "like",
                    "%$filters[search]%")->orWhere("lesson_number", "like", "%$filters[search]%")->orWhere("date",
                    "like", "%$filters[search]%")->orWhere("topic", "like", "%$filters[search]%")->orWhere("subject",
                    "like", "%$filters[search]%")->orWhere("outcome", "like",
                    "%$filters[search]%")->orWhere("outstanding_student", "like",
                    "%$filters[search]%")->orWhere("need_improvement_student", "like",
                    "%$filters[search]%")->orWhere("creator", "like", "%$filters[search]%")->orWhere("approver", "like",
                    "%$filters[search]%");
            });
        }
    }
}
