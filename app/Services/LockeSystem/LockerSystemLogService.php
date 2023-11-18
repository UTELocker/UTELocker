<?php

namespace App\Services\LockeSystem;

use App\Classes\Common;
use App\Exceptions\ApiException;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use App\Models\LockerSystemLog;


class LockerSystemLogService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new LockerSystemLog());
    }

    /**
     * @throws ApiException
     */
    protected function formatInputData(&$inputs): void
    {
        $inputs['license_id'] = $inputs['license_id'] ?? $this->model->license_id;
        $inputs['client_id'] = $inputs['client_id'] ?? $this->model->client_id;
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'license_id', $inputs);
    }

    public function add (array $data): Model
    {
        $this->formatInputData($data);
        $this->setModelFields($data);
        $this->model->save();
        return $this->model;
    }
}
