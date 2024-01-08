<?php

namespace App\Models;

use App\Presenters\EnrollmentPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = EnrollmentPresenter::class;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grade_id',
        'academic_year',
        'student_id',
        'semester',
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
                $qr->where("grade_id", "like", "%$filters[search]%")->orWhere(
                    "academic_year",
                    "like",
                    "%$filters[search]%"
                )->orWhere("student_id", "like", "%$filters[search]%")->orWhere(
                    "semester",
                    "like",
                    "%$filters[search]%"
                );
            });
        }
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
