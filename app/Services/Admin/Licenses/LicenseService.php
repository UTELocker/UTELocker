<?php

namespace App\Services\Admin\Licenses;

use App\Classes\Common;
use App\Models\License;
use App\Services\BaseService;

class LicenseService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new License());
    }

    public function initDefaultData(): static
    {
        $this->model->code = $this->model::generateRandomCode();
        return $this;
    }

    public function add(array $inputs, array $options = [])
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        return;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'locker_id', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'active_at', $inputs);
        Common::assignField($this->model, 'expire_at', $inputs);
    }
}