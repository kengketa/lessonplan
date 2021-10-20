<?php

namespace App\Models;

use App\Presenters\SchoolPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = SchoolPresenter::class;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
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
                $qr->where("name", "like", "%$filters[search]%")->orWhere("address", "like", "%$filters[search]%");
            });
        }
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'school_id');
    }
}
