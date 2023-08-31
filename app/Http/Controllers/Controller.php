<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->siteGroup = siteGroup();
            $this->global = globalSettings();
            $this->siteGroupName = siteGroup() ? siteGroup()->name : $this->global->global_app_name;
            $this->appName = siteGroup() ? siteGroup()->app_name : $this->global->global_app_name;
            $this->locale = siteGroup() ? $this->siteGroup->locale : $this->global->locale;
            config(['app.name' => $this->appName]);

            App::setLocale($this->locale);
            Carbon::setLocale($this->locale);

            setlocale(LC_TIME, $this->locale . '_' . mb_strtoupper($this->locale));

            return $next($request);
        });
    }

    /**
     * @var array
     */
    public array $data = [];

    /**
     * @param mixed $name
     * @param mixed $value
     */
    public function __set(mixed $name, mixed $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get(mixed $name)
    {
        return $this->data[$name];
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function __isset(mixed $name)
    {
        return isset($this->data[$name]);
    }
}
