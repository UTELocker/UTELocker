<?php

namespace App\Models;

use App\Traits\HasSiteGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\UserRole;

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

    public static function getHashCode($licenseId, $options = null): string
    {
        $license = self::findOrFail($licenseId);
        return $license->code_secret . '|' . $license->key_secret . ($options ? '|' . $options : '');
    }

    public static function getAdmins($licenseId)
    {
        return self::where('licenses.id', $licenseId)
            ->leftJoin('clients', 'clients.id', '=', 'licenses.client_id')
            ->leftJoin('users', 'users.client_id', '=', 'clients.id')
            ->where('users.type', UserRole::ADMIN)
            ->select('users.name', 'users.email', 'users.mobile', 'users.id')
            ->get();
    }
}
