<?php

namespace App\Services\Admin\Payments;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Models\PaymentMethod;
use App\Services\BaseService;

class PaymentMethodService extends BaseService
{
    public function __construct(PaymentMethod $model)
    {
        parent::__construct($model);
    }

    public function initDefaultData(): static
    {
        $this->model->config = '{}';
        $this->model->active = CommonConstant::DATABASE_NO;
        return $this;
    }

    public function add(array $inputs)
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['client_id'] = user()->client_id;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'code', $inputs);
        Common::assignField($this->model, 'name', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'active', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
    }

    public function update(array $all, string $id)
    {
        $this->model = $this->get($id);

    }
}
