<?php

namespace App\Services\Admin\Locations;

use App\Classes\Common;
use App\Models\Location;
use App\Models\Locker;
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

    public function getWithLocker()
    {
        return $this->model->select(
            'id',
            'code',
            'description',
            'client_id',
            'latitude',
            'longitude',
        )
            ->where('client_id', user()->client->id)
            ->with('lockers:id,code,description,location_id,image,status')
            ->get();
    }

    public function getLocationsOfLocker($locker)
    {
        $client = Locker::getClient($locker->id);
        if (!isset($client->id)) {
            return [];
        }
        $location =  $this->model->where('client_id', $client->id)->get();
        $locationArr = [];
        foreach ($location as $value) {
            $locationArr[$value->id] = [
                'description' => $value->description,
                'code' => $value->code,
            ];
        }
        return $locationArr;
    }

    public function getLocationsOfClient() {
        return $this->model->where('client_id', user()->client_id)->get();
    }

    public function update(array $all, string $id)
    {
        $this->setModel($this->get($id));
        $this->formatInputData($all);
        $this->setModelFields($all);
        DB::transaction(function () {
            $this->model->save();
        });

    }

    public function delete(string $id)
    {
        $this->setModel($this->get($id));
        if ($this->validateDelete()) {
            return false;
        }
        DB::transaction(function () {
            $this->model->delete();
        });
        return true;
    }

    protected function validateDelete()
    {
        $lockers = $this->model->lockers;
        if (count($lockers) > 0) {
            return true;
        }
        return false;
    }
}
