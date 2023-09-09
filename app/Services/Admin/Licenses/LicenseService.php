<?php

namespace App\Services\Admin\Licenses;

use App\Classes\Common;
use App\Models\License;
use App\Services\BaseService;
use Carbon\Carbon;

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

    public function link($code, $clientId): bool
    {
        $license = $this->model->where('code', $code)->whereNull('client_id')->first();
        if (!$license) {
            return false;
        }
        $license->client_id = $clientId;
        $license->active_at = Carbon::now();
        $warrantyDuration = $license->locker->warranty_duration;
        $license->expire_at = Carbon::now()->addYears($warrantyDuration);
        $license->save();
        return true;
    }
}
