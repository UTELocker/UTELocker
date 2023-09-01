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

    /**
     * @param int $userType
     * @return bool
     */
    public static function canAccess(int $userType): bool
    {
        return auth()->user()->type <= $userType;
    }

    /**
     * @param int $userType
     * @return bool
     */
    public static function hasPermission(int $userType): bool
    {
        return auth()->user()->type === $userType;
    }
}
