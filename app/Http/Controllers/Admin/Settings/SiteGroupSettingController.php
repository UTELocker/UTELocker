<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Classes\Reply;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use App\Models\User;
use App\Services\Admin\Users\UserService;
use DateTimeZone;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Services\Admin\Clients\ClientService;
use App\Http\Requests\Admin\Settings\UpdateSettingRequest;

class SiteGroupSettingController extends BaseSettingController
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        parent::__construct();
        $this->clientService = $clientService;
        $this->pageTitle = __('modules.settings.menu.site-group.menu');
        $this->activeSettingMenu = 'settings-site-group';
        $this->middleware(function ($request, $next) {
            return user()->hasPermission(UserRole::ADMIN)
                ? $next($request)
                : redirect()->route('admin.dashboard');
        });
    }

    public function index()
    {
        $this->editPermission = User::hasPermission(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $tab = request('tab');
        $this->view = 'admin.settings.siteGroup.ajax.siteGroupSettings';
        $this->activeTab = $tab ?: 'siteGroupSettings';
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

        return view('admin.settings.siteGroup.index', $this->data);
    }

    public function update(UpdateSettingRequest $request, string $id)
    {
        $this->editPermission = User::hasPermission(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->client = $this->clientService->get($id);
        $form = $request->only(['date_format', 'locale', 'time_format', 'timezone']);
        $this->clientService->update($this->client, $form, ['isPrefix' => false]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.dashboard');
        }
        clearSessionSettings('siteGroup');
        return Reply::successWithData(__('messages.recordUpdated'), ['redirectUrl' => $redirectUrl]);
    }
}
