<?php

namespace App\Services;

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

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function new(): Model
    {
        $this->model = $this->model->newInstance();
        $this->initDefaultData();
        return $this->model;
    }

    public function initDefaultData(): static
    {
        return $this;
    }

    abstract protected function formatInputData(&$inputs);

    abstract protected function setModelFields($inputs);
}
