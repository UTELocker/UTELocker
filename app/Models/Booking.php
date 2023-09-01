<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Traits\BelongsToThrough;

class Booking extends Model
{
    use HasSiteGroup;
    use BelongsToThrough;

    public function lockerSlot(): BelongsTo
    {
        return $this->belongsTo(LockerSlot::class);
    }

    public function locker()
    {
        return $this->belongsToThrough(Locker::class, LockerSlot::class);
    }
}
