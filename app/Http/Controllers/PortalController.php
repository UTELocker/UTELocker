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

        return view(
            'layouts.portal',
            [
                'pageTitle' => 'UTELocker Portal',
                'user' => $user,
                'siteGroupSettings' => siteGroup(),
            ]
        );
    }
}
