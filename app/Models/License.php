<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    use HasSiteGroup;

    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }
}
