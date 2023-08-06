<?php

use App\Models\GlobalSetting;

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
