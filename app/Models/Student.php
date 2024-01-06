<?php

namespace App\Models;

use App\Presenters\StudentPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = StudentPresenter::class;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'code',
        'prefix',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
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
                $qr->where("code", "like", "%$filters[search]%")->orWhere(
                    "prefix",
                    "like",
                    "%$filters[search]%"
                )->orWhere("first_name", "like", "%$filters[search]%")->orWhere(
                    "last_name",
                    "like",
                    "%$filters[search]%"
                )->orWhere("email", "like", "%$filters[search]%")->orWhere(
                    "password",
                    "like",
                    "%$filters[search]%"
                )->orWhere("phone", "like", "%$filters[search]%");
            });
        }
    }
}
