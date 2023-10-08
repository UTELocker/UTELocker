<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Classes\Reply;
use App\Enums\UserRole;
use App\Models\GlobalSetting;
use App\Models\User;
use DateTimeZone;
use Illuminate\Http\Request;
use App\Services\Admin\Settings\SettingService;
use App\Http\Requests\Admin\Settings\UpdateSettingRequest;

class AppSettingController extends BaseSettingController
{
    protected SettingService $settingService;
    public function __construct(SettingService $settingService)
    {
        parent::__construct();
        $this->pageTitle = __('modules.settings.settings');
        $this->activeSettingMenu = 'settings-app';
        $this->settingService = $settingService;
        $this->middleware(function ($request, $next) {
            return user()->canAccess(UserRole::ADMIN)
                ? $next($request)
                : redirect()->route('admin.dashboard');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->editPermission = User::hasPermission(UserRole::SUPER_USER);
        if (!$this->editPermission) {
            abort(403);
        }
        $tab = request('tab');
        $this->view = 'admin.settings.app.ajax.general';
        $this->activeTab = $tab ?: 'general';
        $this->timezones = DateTimeZone::listIdentifiers();
        $this->dateFormat = array_keys(GlobalSetting::DATE_FORMATS);
        $this->dateObject = now();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly([
                'status' => 'success',
                'html' => $html,
                'title' => $this->pageTitle
            ]);
        }

        return view('admin.settings.app.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request, string $id)
    {
        $this->editPermission = User::hasPermission(UserRole::SUPER_USER);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->globalSetting = $this->settingService->get($id);
        $form = $request->only(['date_format', 'locale', 'time_format', 'timezone']);
        $this->settingService->update($this->globalSetting, $form, ['isPrefix' => false]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.dashboard');
        }

        return Reply::successWithData(__('messages.recordUpdated'), ['redirectUrl' => $redirectUrl]);
    }
}
