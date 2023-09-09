<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Classes\Files;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LanguageSetting;
use Illuminate\Http\Request;
use App\Classes\Reply;
use App\Enums\UserRole;
use App\Models\GlobalSetting;
use App\Models\User;
use App\Services\Admin\Users\UserService;

use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class ProfileSettingController extends BaseSettingController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->pageTitle = __('modules.settings.menu.profile.menu');
        $this->activeSettingMenu = 'settings-profile';
        $this->middleware(function ($request, $next) {
            return user()->canAccess(UserRole::ADMIN)
                ? $next($request)
                : redirect()->route('admin.dashboard');
        });
    }

    public function index()
    {
        $tab = request('tab');
        $this->view = 'admin.settings.profile.ajax.profileSettings';
        $this->activeTab = $tab ?: 'profileSettings';
        $this->user = $this->userService->get(Auth::user()->id);
        $this->user_avatar = Files::getImageUrl($this->user->avatar, Files::USER_AVATAR_FOLDER, Files::USER_UPLOAD_FOLDER);
        $this->clients = Client::getClientList();
        $this->languages = LanguageSetting::getEnabledLanguages();
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly([
                'status' => 'success',
                'html' => $html,
                'title' => $this->pageTitle
            ]);
        }

        return view('admin.settings.profile.index', $this->data);
    }
}
