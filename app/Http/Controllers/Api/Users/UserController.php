<?php

namespace App\Http\Controllers\Api\Users;

use App\Classes\Reply;
use App\Enums\TokenType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\GetClientByEmailRequest;
use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Services\Admin\Clients\ClientService;
use App\Services\Admin\Users\UserService;
use App\Services\Wallets\WalletService;
use App\Traits\ManageCustomToken;
use App\View\Components\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ManageCustomToken;
    public ?UserService $userService;

    public ?WalletService $walletService;
    public ?ClientService $clientService;

    public function __construct(
        UserService $userService,
        WalletService $walletService,
        ClientService $clientService
    ) {
        $this->userService = $userService;
        $this->walletService = $walletService;
        $this->clientService = $clientService;
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

    public function getListClientForGuest(Request $request)
    {
        $token = $request->get('token');
        if ($token) {
            $result = $this->verifyToken($token);
            if (!$result) {
                return Reply::error('Token is invalid');
            }
            $listClientGuest = $this->clientService->getListClientForGuest($result->client_id);

        } else {
            $listClientGuest = $this->clientService->getListClientForGuest();
        }
        return Reply::successWithData(
            'List client guest',
            [
                'data' => $listClientGuest,
            ]
        );
    }

    public function getTokenRegister()
    {
        return Reply::successWithData('Get tokens successfully', [
            'data' => $this->getTokenOfClient(TokenType::AUTH)
        ]);
    }

    public function createTokenRegister()
    {
        return Reply::successWithData('Create token successfully', [
            'token' => $this->createCustomToken()
        ]);
    }

    public function deleteTokenRegister($id) {
        $this->deleteToken($id);
        return Reply::success('Delete token successfully');
    }
}
