<?php

namespace App\Services\Admin\HelpCalls;

use App\Classes\Common;
use App\Models\HelpCallStdProblems;
use App\Services\BaseService;

class HelpCallStdProblemsService extends BaseService {

    public function __construct()
    {
        parent::__construct(new HelpCallStdProblems());
    }

    public function add(array $all)
    {
        $this->new();
        $this->formatInputData($all);
        $this->setModelFields($all);
        $this->model->save();
        return $this->model;
    }

    public function update($id, array $all)
    {
        $this->setModel($this->get($id));
        $this->formatInputData($all);
        $this->setModelFields($all);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['client_id'] = user()->client_id;
        $inputs['type'] = $inputs['type'] ?? $this->model->type;
        $inputs['description'] = $inputs['description'] ?? $this->model->description;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'description', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
    }

    public function getAllOfClient()
    {
        return $this->model
            ->where('client_id', user()->client_id)
            ->get();
    }

    public function delete($id)
    {
        $helpCallStdProblem = $this->get($id);
        $helpCallStdProblem->delete();
        return $helpCallStdProblem;
    }
}
