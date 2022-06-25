<?php

namespace App\Models;

use App\Presenters\SchoolPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'subjects',
        'lat',
        'lng',
        'radius'
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
        'subjects' => 'json'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */

    public function scopeFilter(Builder $query, array $filters): void
    {
        $user = Auth::user();
        if ($user->roles[0]->name === Role::ROLE_TEACHER) {
            $query->whereHas('teachers', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        if (!empty($filters["search"])) {
            $query->where(function ($qr) use ($filters) {
                $qr->where("name", "like", "%$filters[search]%");
            });
        }
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'school_id')
            ->orderBy('type', 'asc')
            ->orderBy('level');
    }

    public function reports()
    {
        return $this->hasManyThrough(Report::class, Grade::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'school_teachers', 'school_id', 'teacher_id');
    }
}
