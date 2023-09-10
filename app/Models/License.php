<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    use HasSiteGroup;

    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }

    public static function generateRandomCode(): string
    {
        $code = 'LIC-' . date('Y') . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
        $lastLicense = self::where('code', $code)->first();
        if ($lastLicense) {
            return self::generateRandomCode();
        }
        return $code;
    }
}
