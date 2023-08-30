<?php

namespace App\Traits;

use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSiteGroup
{
    public function siteGroup(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
