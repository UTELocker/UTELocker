<?php

namespace App\Models;

use App\Enums\LockerSlotStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locker extends Model
{
    public function lockerSlots(): HasMany
    {
        return $this->hasMany(LockerSlot::class);
    }

    public function lockerSlotAvailable(): HasMany
    {
        return $this->hasMany(LockerSlot::class)
            ->where('status', LockerSlotStatus::AVAILABLE);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
