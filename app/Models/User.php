<?php

namespace App\Models;

use App\Presenters\UserPresenter;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Presentable;

    protected $presenter = UserPresenter::class;

    public const ROLES = [
        Role::ROLE_USER => self::ROLE_USER, // 'USER' => 1
        Role::ROLE_ADMIN => self::ROLE_ADMIN, // 'ADMIN' => 90
        Role::ROLE_SUPER_ADMIN => self::ROLE_SUPER_ADMIN, // 'SUPER_ADMIN' => 100
    ];

    public const ROLE_USER = 1;

    public const ROLE_ADMIN = 90;

    public const ROLE_SUPER_ADMIN = 100;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function scopeFilter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->where(function ($qr) use ($filters) {
                $qr->where('name', 'like', "%$filters[search]%")
                    ->orWhere('email', 'like', "%$filters[search]%");
            });
        }
        if (isset($filters['role'])) {
            $query->role($filters['role']);
        }
    }
}
