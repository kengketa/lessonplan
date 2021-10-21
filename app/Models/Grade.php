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

    public const NURSERY_TYPE = 1;
    public const KINDERGATEN_TYPE = 2;
    public const PRIMARY_TYPE = 3;
    public const SECONDARY_TYPE = 4;

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

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'grade_id');
    }

}
