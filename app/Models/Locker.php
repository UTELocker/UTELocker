<?php

namespace App\Models;

use App\Enums\LockerSlotStatus;
use App\Enums\LockerSlotType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Znck\Eloquent\Traits\BelongsToThrough;

class Locker extends Model
{
    use BelongsToThrough;

    public const PREFIX_CODE = 'LOCKER';

    public function lockerSlots(): HasMany
    {
        return $this->hasMany(LockerSlot::class);
    }

    public function lockerSlotAvailable(): HasMany
    {
        return $this->hasMany(LockerSlot::class)
            ->where('status', LockerSlotStatus::AVAILABLE);
    }

    public function lockerSlotType(): HasMany
    {
        return $this->hasMany(LockerSlot::class)
            ->where('type', LockerSlotType::SLOT);
    }

    public function lockerCPUType(): HasMany
    {
        return $this->hasMany(LockerSlot::class)
            ->where('type', LockerSlotType::CPU);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public static function generateNextCode(): string
    {
        $code = self::PREFIX_CODE;
        $lastLocker = self::orderBy('id', 'desc')->first();
        if ($lastLocker) {
            $code .= str_pad($lastLocker->id + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $code .= '0001';
        }
        return $code;
    }

    public static function getClient($lockerId)
    {
        $license = License::where('locker_id', $lockerId)->first();
        return Client::where('id', $license->client_id)->first();
    }
}
