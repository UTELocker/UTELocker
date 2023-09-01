<?php

namespace App\Traits;

use App\Enums\UserRole;
use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSiteGroup
{
    public function siteGroup(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function scopeSiteGroup($query)
    {
        if (user()->type === UserRole::SUPER_USER) {
            return $query;
        }

        return $query->where('client_id', user()->client_id);
    }
}
