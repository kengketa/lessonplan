<?php

namespace App\Models;

use App\Presenters\MeetingPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{

    use HasFactory;
    use Presentable;

    protected $presenter = MeetingPresenter::class;

    public const UP_COMMING = 1;
    public const ACHIEVED = 2;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'title',
        'date',
        'status',
        'location',
        'attendee'
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
        'attendee' => 'json'
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
                $qr->where("title", "like", "%$filters[search]%")->orWhereHas('agendas', function ($q) use ($filters) {
                    $q->where("topic", "like", "%$filters[search]%")
                        ->orWhere("detail", "like", "%$filters[search]%")
                        ->orWhere("decision", "like", "%$filters[search]%");
                });
            });
        }
        if (!empty($filters['school'])) {
            $query->where('school_id', $filters['school']);
        }
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }
}
