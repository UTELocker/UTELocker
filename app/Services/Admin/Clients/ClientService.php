<?php

namespace App\Services\Admin\Clients;

use App\Models\SiteGroup;
use App\Services\BaseService;

class ClientService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new SiteGroup());
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
