<?php

namespace App\Traits;

use App\Models\SiteGroup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSiteGroup
{
    public function siteGroup(): BelongsTo
    {
        return $this->belongsTo(SiteGroup::class);
    }
}
