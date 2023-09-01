<?php

namespace App\Models;

use App\Enums\LockerSlotStatus;
use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Location extends Model
{
    use HasSiteGroup;

    public function lockers(): HasMany
    {
        return $this->hasMany(Locker::class);
    }

    public function lockerSlots(): HasManyThrough
    {
        return $this->hasManyThrough(LockerSlot::class, Locker::class);
    }

    public function lockerSlotsAvailables(): HasManyThrough
    {
        return $this->hasManyThrough(LockerSlot::class, Locker::class)
            ->where('status', LockerSlotStatus::AVAILABLE);
    }

    public function locationType(): BelongsTo
    {
        return $this->belongsTo(LocationType::class);
    }
}
