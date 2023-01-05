<?php

namespace App\Models;

use App\Presenters\MisbehaviorPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Presentable;

class Misbehavior extends Model
{
    use HasFactory, Presentable;

    protected $presenter = MisbehaviorPresenter::class;

    protected $fillable = [
        'subject',
        'teacher_id',
        'report_id',
        'behavior',
        'name',
        'grade'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
