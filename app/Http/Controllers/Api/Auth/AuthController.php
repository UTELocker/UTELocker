<?php

namespace App\Http\Controllers\Api\Auth;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Admin\Users\UserService;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller {

    public  ?AuthService $authService;
    public UserService $userService;

    public function __construct(
        AuthService $authService,
        UserService $userService
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function login(Request $request) {
        $res = $this->authService->login($request->all());
        if ($res) {
            return Reply::successWithData(
                'Login successfully',
                [
                    'data' => $res,
                ]
            );
        }
        return Reply::error('Login failed');
    }

    public function logout() {
        $this->authService->logout();
        return Reply::success('Logout successfully');
    }
}
