<?php

namespace App\Http\Controllers;

use App\Services\Admin\Users\UserService;

class PortalController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function index()
    {
        if (user()->isSuperUser()) {
            return redirect()->route('admin.dashboard');
        }

        $user = $this->userService->lookups(user());
        $configFireBase = [
            'apiKey' => globalSettings()->firebase_api_key,
            'authDomain' => globalSettings()->firebase_auth_domain,
            'projectId' => globalSettings()->firebase_project_id,
            'storageBucket' => globalSettings()->firebase_storage_bucket,
            'messagingSenderId' => globalSettings()->firebase_messaging_sender_id,
            'appId' => globalSettings()->firebase_app_id,
            'measurementId' => globalSettings()->firebase_measurement_id,
        ];
        return view(
            'layouts.portal',
            [
                'pageTitle' => 'UTELocker Portal',
                'user' => $user,
                'siteGroupSettings' => siteGroup(),
                'configFireBase' => $configFireBase,
            ]
        );
    }
}
