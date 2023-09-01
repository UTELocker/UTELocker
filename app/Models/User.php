<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasSiteGroup;


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function roles(): array
    {
        return UserRole::getDescriptions();
    }

    public function getRoleAttribute(): string
    {
        return self::roles()[$this->attributes['role']];
    }

    public static function isSuperUser($userId): bool
    {
        return self::find($userId)->type === UserRole::SUPER_USER;
    }

    public static function isAdmin($userId): bool
    {
        return self::find($userId)->type === UserRole::ADMIN;
    }

    public static function isNormalUser($userId): bool
    {
        return self::find($userId)->type === UserRole::NORMAL;
    }

    public static function hasPermission(int $userType): bool
    {
        return auth()->user()->type <= $userType;
    }
}
