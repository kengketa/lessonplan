<?php

namespace App\Models;

use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    public const ROLE_USER = 'USER';
    public const ROLE_TEACHER = 'TEACHER';
    public const ROLE_SCHOOL_ADMIN = 'SCHOOL_ADMIN';
    public const ROLE_SCHOOL_VICE_DIRECTOR = 'SCHOOL_VICE_DIRECTOR';
    public const ROLE_SCHOOL_DIRECTOR = 'SCHOOL_DIRECTOR';
    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_SUPER_ADMIN = 'SUPER_ADMIN';
}
