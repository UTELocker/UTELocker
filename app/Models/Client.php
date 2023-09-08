<?php

namespace App\Models;

use App\Classes\CommonConstant;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Client extends Model
{
    public function scopeHasPermission($query)
    {
        if (user()->type === UserRole::SUPER_USER) {
            return $query;
        }

        return $query->where('id', user()->client_id);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function locationTypes(): HasMany
    {
        return $this->hasMany(LocationType::class);
    }

    public function lockers(): HasManyThrough
    {
        return $this->hasManyThrough(Locker::class, License::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public static function getClientList()
    {
        if (User::hasPermission(UserRole::SUPER_USER)) {
            return self::get();
        }
        return self::where('id', user()->client_id)->get();
    }
}
