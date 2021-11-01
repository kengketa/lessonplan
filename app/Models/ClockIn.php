<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClockIn extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'school_id', 'date', 'clock_in', 'clock_out'];

    public function scopeFilter(Builder $query, array $filters): void
    {
        if (count($filters) == 0) {
            return;
        }
        if (isset($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }
        if (isset($filters['month'])) {
            $arr = explode('-', $filters['month']);
            $year = $arr[0];
            $month = $arr[1];
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id')->withTrashed();
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
