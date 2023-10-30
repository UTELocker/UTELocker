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

    public static function getCode($lockerId, $lockerSlotId)
    {
        $lockerSlots = self::where('locker_id', $lockerId)->get();
        $code = 0;
        foreach ($lockerSlots as $slot) {
            if ($slot->type == LockerSlotType::SLOT) {
                $code++;
            }
            if ($slot->id == $lockerSlotId) {
                break;
            }
        }
        return $code;
    }
}
