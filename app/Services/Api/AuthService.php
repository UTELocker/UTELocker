<?php

namespace App\Services\Api;

use App\Classes\Common;
use App\Models\User;
use App\Services\BaseService;

class AuthService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function login($inputs)
    {
        $this->model = $this->getAccountByEmail($inputs['email'], $inputs['clientId']);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        if (password_verify($this->model->password, $this->model->getOriginal('password'))) {
            $token = $this->model->createToken('token-name')->plainTextToken;
            $this->model->accessToken = $token;
            return $this->model;
        }

        return null;
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['client_id'] = $inputs['clientId'];
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'email', $inputs);
        Common::assignField($this->model, 'password', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
    }

    protected function getAccountByEmail($email, $clientId) {
        return $this->model
            ->where('email', $email)
            ->where('client_id', $clientId)
            ->first();
    }
}
