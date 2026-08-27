<?php

namespace App\Models;

use App\Presenters\SubstitutePresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    /**
     * How long a cached API response stays fresh, in seconds.
     */
    public const API_CACHE_TTL = 60 * 15;

    /**
     * Holds the generation number that prefixes every API cache key.
     */
    public const API_CACHE_VERSION_KEY = 'cache_substitutes_api_version';

    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            if ($model instanceof Substitute) {
                self::bumpApiCacheVersion();
            }
        });
        static::deleted(function ($model) {
            if ($model instanceof Substitute) {
                self::bumpApiCacheVersion();
            }
        });
    }

    public static function apiCacheVersion(): int
    {
        return (int) Cache::get(self::API_CACHE_VERSION_KEY, 1);
    }

    /**
     * Invalidate every cached API response by moving the key generation forward.
     * Cheaper than tracking each key, and the file cache driver has no tag support.
     */
    public static function bumpApiCacheVersion(): void
    {
        Cache::forever(self::API_CACHE_VERSION_KEY, self::apiCacheVersion() + 1);
    }
}
