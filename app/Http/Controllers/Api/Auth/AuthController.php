<?php

namespace App\Http\Controllers\Api\Auth;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Services\Admin\Users\UserService;
use App\Services\Api\AuthService;

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

    public function login(LoginRequest $request) {
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
