<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationType extends Model
{
    use HasSiteGroup;

    public static function getLocationTypeList()
    {
        if (User::hasPermission(\App\Enums\UserRole::SUPER_USER)) {
            return self::get();
        }
        return self::where('client_id', user()->client_id)->get();
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
