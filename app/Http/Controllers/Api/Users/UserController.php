<?php

namespace App\Http\Controllers\Api\Users;

use App\Classes\Reply;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\GetClientByEmailRequest;
use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Services\Admin\Users\UserService;
use App\Services\Wallets\WalletService;

class UserController extends Controller
{
    public ?UserService $userService;

    public ?WalletService $walletService;

    public function __construct(UserService $userService, WalletService $walletService)
    {
        $this->userService = $userService;
        $this->walletService = $walletService;
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
        $role = $this->userService->getByEmail($email)->type;
        $listClient = $role === UserRole::SUPER_USER ?
            [] :
            $this->userService->getListClientByEmail($email);
        return Reply::successWithData(
            'List client',
            [
                'data' => $listClient,
                'role' => $role,
            ]
        );
    }

    public function update(UpdateUserRequest $request) {
        $user = user();
        $data = $request->all();
        if (isset($data['password_is2FA'])) {
            $wallet = $this->walletService->get(user()->id);
            $wallet = $this->walletService->update($wallet, $data);
            if (!$wallet) {
                return Reply::error('Old password is wrong');
            }
        } else {
            $user = $this->userService->update($user, $data);
            if (!$user) {
                return Reply::error('Old password is wrong');
            }
        }

        return Reply::successWithData(
            'Update user success',
            [
                'data' => $user,
            ]
        );
    }

    public function getListAdmin() {
        $listAdmin = $this->userService->getListAdmin();
        return Reply::successWithData(
            'List admin',
            [
                'data' => $listAdmin,
            ]
        );
    }
}
