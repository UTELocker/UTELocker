<?php

namespace App\Services\Admin\Lockers;

use App\Classes\Common;
use App\Models\LockerSlot;
use App\Services\BaseService;

class LockerSlotService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new LockerSlot());
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
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'row', $inputs);
        Common::assignField($this->model, 'column', $inputs);
        Common::assignField($this->model, 'config', $inputs);
        Common::assignField($this->model, 'status', $inputs);
    }
}
