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
class SiteGroupSettingController extends BaseSettingController
{
    protected ClientService $clientService;
    public const FORM_PREFIX = 'client_';

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

    private function getDataWithPrefix($prefix, $form): array
    {
        return array_filter($form, function ($key) use ($prefix) {
            return str_starts_with($key, $prefix);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function update(Request $request, string $id)
    {
        $this->editPermission = User::hasPermission(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }

        $this->client = $this->clientService->get($id);
        $form =  $request->only(['date_format', 'time_format', 'timezone', 'locale']);
        $this->clientService->update($this->client, $form, ['isPrefix' => false]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.dashboard');
        }

        return Reply::successWithData(__('messages.recordUpdated'), ['redirectUrl' => $redirectUrl]);
    }
}
