<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasSiteGroup;

    public const ROLE_SUPER_USER = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_NORMAL_USER = 2;
    public const GENDER_MALE = 0;
    public const GENDER_FEMALE = 1;
    public const GENDER_OTHER = 2;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function roles(): array
    {
        return [
            self::ROLE_SUPER_USER => __('app.superUser'),
            self::ROLE_ADMIN => __('app.admin'),
            self::ROLE_NORMAL_USER => __('app.user'),
        ];
    }

    public function getRoleAttribute(): string
    {
        return self::roles()[$this->attributes['role']];
    }

    public static function isSuperUser($userId): bool
    {
        return self::find($userId)->user_type === self::ROLE_SUPER_USER;
    }

    public static function isAdmin($userId): bool
    {
        return self::find($userId)->user_type === self::ROLE_ADMIN;
    }

    public static function isNormalUser($userId): bool
    {
        return self::find($userId)->user_type === self::ROLE_NORMAL_USER;
    }
}
