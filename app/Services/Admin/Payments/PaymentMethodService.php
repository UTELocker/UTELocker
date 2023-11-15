<?php

namespace App\Services\Admin\Payments;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Libs\PaymentMethodConfig\PaymentMethodLoader;
use App\Models\PaymentMethod;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function add(array $inputs): Model
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs): void
    {
        $inputs['client_id'] = isset($inputs['client_id']) ? $inputs['client_id'] : $this->model->client_id ?? user()->client_id;
        $inputs['code'] = $inputs['code'] ?? $this->model->code;
        $inputs['type'] = $inputs['type'] ?? $this->model->type;
        $inputs['config'] = $this->getPaymentMethodConfig($inputs);
        $inputs['active'] = $inputs['active'] ?? $this->model->active ?? CommonConstant::DATABASE_NO;
    }

    private function getPaymentMethodConfig(array $inputs): string
    {
        $type = $inputs['type'];
        $paymentMethodConfig = PaymentMethodLoader::load($type, PaymentMethodLoader::getConfigFromInputs($inputs));
        return $paymentMethodConfig->build();
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'code', $inputs);
        Common::assignField($this->model, 'name', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'active', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'config', $inputs);
    }

    public function update(array $inputs, string $id)
    {
        $this->model = $this->get($id);
        $inputs = Common::removeField($inputs, 'code');
        $inputs = Common::removeField($inputs, 'type');
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        DB::transaction(function () {
            $this->model->save();
        });

        return $this->model;
    }

    public function getAll()
    {
        return PaymentMethod::userSiteGroup()
            ->where('active', CommonConstant::DATABASE_YES)
            ->select([
                'id',
                'code',
                'name',
                'type'
            ])
            ->get();
    }

    public function getOfClient()
    {
        return $this->model
            ->where('client_id', user()->client_id)
            ->select([
                'name',
            ])
            ->get();
    }

    public function getFullDetail($id)
    {
        return $this->model
            ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->leftJoin('bookings', 'bookings.transaction_id', '=', 'transactions.id')
            ->where('users.client_id', user()->client_id)
            ->select(
                'transactions.*',
                'users.name as user_name',
                'payment_methods.name as payment_method_name'
            )
            ->findOrFail($id);
    }
}
