<?php

use App\Models\GlobalSetting;
use App\Models\SiteGroup;

if (!function_exists('globalSettings')) {
    function globalSettings()
    {
        if (!cache()->has('globalSettings')) {
            $setting = GlobalSetting::first();
            cache(['globalSettings' => $setting]);

            return $setting;
        }

        return cache('globalSettings');
    }
}

if (!function_exists('user')) {
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }

        $user = auth()->user();

        if ($user) {
            session(['user' => $user]);

            return session('user');
        }

        return null;
    }
}

if (!function_exists('siteGroup')) {
    function siteGroup()
    {
        if (session()->has('siteGroup')) {
            return session('siteGroup');
        }

        if (user()) {
            if (user()->siteGroup) {
                $siteGroup = SiteGroup::find(user()->siteGroup->id);
                session(['siteGroup' => $siteGroup]);

                return $siteGroup;
            }

            return session('siteGroup');
        }

        return false;
    }
}
