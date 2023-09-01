<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LockerSlot extends Model
{
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }
}
