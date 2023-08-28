<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $appends = ['logo_url', 'favicon_url', 'login_background_url'];

    public function getLogoUrlAttribute()
    {
        return '';
    }

    public function getFaviconUrlAttribute()
    {
        return '';
    }

    public function getLoginBackgroundUrlAttribute()
    {
        return '';
    }

    public function scopeHasPermission($query)
    {
        if (user()->type === User::ROLE_SUPER_USER) {
            return $query;
        }

        return $query->where('id', user()->client_id);
    }
}
