<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationType extends Model
{
    use HasSiteGroup;

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
