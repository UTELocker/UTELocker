<?php

namespace App\Models;

use App\Enums\LockerSlotType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LockerSlot extends Model
{
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
