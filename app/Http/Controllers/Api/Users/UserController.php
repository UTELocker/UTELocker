<?php

namespace App\Http\Controllers\Api\Users;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\GetClientByEmailRequest;
use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Services\Admin\Users\UserService;

class UserController extends Controller
{
    public ?UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get()
    {
        $user = auth()->user();
        return Reply::successWithData(
            'User detail',
            [
                'data' => $user,
            ]
        );
    }

    public function getListClient(GetClientByEmailRequest $request){
        $email = $request->get('email');
        $listClient = $this->userService->getListClientByEmail($email);
        return Reply::successWithData(
            'List client',
            [
                'data' => $listClient,
            ]
        );
    }

    public function update(UpdateUserRequest $request) {
        $user = auth()->user();
        $data = $request->all();
        $user = $this->userService->update($user, $data);
        return Reply::successWithData(
            'Update user success',
            [
                'data' => $user,
            ]
        );
    }
}
