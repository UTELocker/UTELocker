<?php

use App\Models\GlobalSetting;
use App\Models\LanguageSetting;

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

        if (user()?->siteGroup) {
            $siteGroup = user()->siteGroup;
            session(['siteGroup' => $siteGroup]);

            return $siteGroup;
        }

        return false;
    }

    function clearSessionSettings()
    {
        session()->forget('siteGroup');
        session()->forget('user');
    }
}

if (!function_exists('companyOrGlobalSetting')) {
    function siteGroupOrGlobalSetting()
    {
        if (session()->has('siteGroupOrGlobalSetting')) {
            return session('siteGroupOrGlobalSetting');
        }

        if (user()) {
            if (user()->siteGroup) {
                $siteGroup = user()->siteGroup;
                session(['siteGroupOrGlobalSetting' => $siteGroup]);

                return $siteGroup;
            }
            return globalSettings();
        }

        return globalSettings();
    }
}

if (!function_exists('languageSettings')) {

    function languageSettings()
    {
        if (!cache()->has('languageSettings')) {
            cache(['languageSettings' => LanguageSetting::getEnabledLanguages()]);
        }

        return cache('languageSettings');
    }

}

