<?php

namespace App\Models;

use App\Presenters\SubstitutePresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substitute extends Model
{
    use HasFactory;
    use Presentable;

    protected $presenter = SubstitutePresenter::class;

    protected $fillable = [
        'school_id',
        'date',
        'start_time',
        'end_time',
        'subject',
        'teacher',
        'volunteer',
        'grade'
    ];
    protected $casts = [
        'school_id' => 'integer',
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',

    ];
}
