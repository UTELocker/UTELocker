<?php

namespace App\Services\Admin\Locations;

use App\Classes\Common;
use App\Models\LocationType;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class LocationTypeSerivce extends BaseService
{
    public function __construct(LocationType $model)
    {
        parent::__construct($model);
    }

    public function add(array $inputs)
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        DB::transaction(function () {
            $this->model->save();
        });
    }

    protected function formatInputData(&$inputs)
    {
        if (!user()->isSuperUser()) {
            $inputs['client_id'] = user()->client_id;
        } else {
            $inputs['client_id'] = $inputs['client_id'] ?? $this->model->client_id;
        }
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'code', $inputs);
        Common::assignField($this->model, 'description', $inputs);
    }

    public function update($locationType, array $form)
    {
        $this->setModel($locationType);
        $this->formatInputData($form);
        $this->setModelFields($form);

        DB::transaction(function () {
            $this->model->save();
        });
    }
}
