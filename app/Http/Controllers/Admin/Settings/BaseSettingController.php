<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;

class BaseSettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->common();

            return $next($request);
        });
    }

    private function common()
    {
        $this->fields = [];
        $this->languageSettings = languageSettings();
    }
}
