<?php

namespace App\Services\Admin\Users;

use App\Models\User;
use App\Services\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function add(&$id, $inputs, array $options = [])
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        return true;
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['password'] = bcrypt($inputs['password']);
    }

    protected function setModelFields($inputs)
    {
        $this->model->fill($inputs);
    }
}
