<?php

namespace App\Services\Admin\Locations;

use App\Classes\Common;
use App\Models\Location;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class LocationService extends BaseService
{
    public function __construct(Location $model)
    {
        parent::__construct($model);
    }

    public function initDefaultData(): static
    {
        $this->model->latitude = Location::DEFAULT_LATITUDE;
        $this->model->longitude = Location::DEFAULT_LONGITUDE;
        return $this;
    }

    public function add(array $inputs)
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        DB::transaction(function () {
            $this->model->save();
        });

        return $this->model;
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
        Common::assignField($this->model, 'code', $inputs);
        Common::assignField($this->model, 'description', $inputs);
        Common::assignField($this->model, 'location_type_id', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'latitude', $inputs);
        Common::assignField($this->model, 'longitude', $inputs);
    }
}
