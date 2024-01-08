<?php

namespace App\Models;

use App\Presenters\GradePresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Grade extends Model
{
    use HasFactory;
    use Presentable;

    public const NURSERY_TYPE = 1;
    public const KINDERGATEN_TYPE = 2;
    public const PRIMARY_TYPE = 3;
    public const SECONDARY_TYPE = 4;
    protected $presenter = GradePresenter::class;
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

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model instanceof Grade) {
                $schoolId = $model->school->id;
                Cache::forget('cache_school_id_' . $schoolId);
            }
        });
    }


    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'grade_id');
    }

    public function vocabs()
    {
        return $this->hasMany(Vocab::class, 'grade_id')->orderBy('created_at', 'desc');
    }


    public function getFullNameAttribute()
    {
        $prefix = "";
        switch ($this->type) {
            case Grade::NURSERY_TYPE:
                $prefix = 'preschool';
                break;
            case Grade::KINDERGATEN_TYPE:
                $prefix = 'kindergarten';
                break;
            case Grade::PRIMARY_TYPE:
                $prefix = 'primary';
                break;
            case Grade::SECONDARY_TYPE:
                $prefix = 'secondary';
                break;
        }
        return $prefix . '-' . $this->level;
    }

    public function enrollmentThisSemester()
    {
        return $this->hasMany(Enrollment::class, 'grade_id')
            ->where('academic_year', getCurrentAcademicYear())
            ->where('semester', getCurrentSemester());
    }

}
