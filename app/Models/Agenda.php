<?php

namespace App\Models;

use App\Presenters\AgendaPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = AgendaPresenter::class;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id','topic','detail','decision',
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
        if (! empty($filters["search"])) {
            $query->where(function ($qr) use ($filters) {
                $qr->where("meeting_id", "like", "%$filters[search]%")->orWhere("topic", "like", "%$filters[search]%")->orWhere("detail", "like", "%$filters[search]%")->orWhere("decision", "like", "%$filters[search]%");
            });
        }
    }
}
