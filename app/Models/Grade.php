<?php

namespace App\Models;

use App\Presenters\GradePresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    use Presentable;

    protected $presenter = GradePresenter::class;

    public const NURSERY_LEVEL = 1;
    public const KINDERGATEN_LEVEL = 2;
    public const PRIMARY_LEVEL = 3;
    public const SECONDARY_LEVEL = 4;

    protected $fillable = [
        'school_id',
        'type',
        'level',
        'room_number',
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
                $qr->where("school_id", "like", "%$filters[search]%")->orWhere("type", "like",
                    "%$filters[search]%")->orWhere("level", "like", "%$filters[search]%")->orWhere("room_number",
                    "like", "%$filters[search]%");
            });
        }
    }
}
