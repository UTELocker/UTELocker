<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    public function new(): Model
    {
        $this->model = $this->model->newInstance();
        return $this->model;
    }

    protected function initDefaultData(): void
    {
        // override this method to init default data
    }

    protected function formatInputData(&$inputs)
    {
        // override this method to format input data
    }

    protected function setModelFields($inputs)
    {
        // override this method to set model fields
    }
}
