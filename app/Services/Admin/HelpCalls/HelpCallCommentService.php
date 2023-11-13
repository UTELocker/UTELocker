<?php

namespace App\Services\Admin\HelpCalls;

use App\Classes\Common;
use App\Models\HelpCallComment;
use App\Services\BaseService;

class HelpCallCommentService extends BaseService {

    public function __construct()
    {
        parent::__construct(new HelpCallComment());
    }

    public function add(array $all)
    {
        $this->new();
        $this->formatInputData($all);
        $this->setModelFields($all);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['client_id'] = user()->client_id;
        $inputs['owner_id'] = $inputs['owner_id'] ?? user()->id;
        $inputs['content'] = $inputs['content'] ?? $this->model->content;

    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'content', $inputs);
        Common::assignField($this->model, 'owner_id', $inputs);
        Common::assignField($this->model, 'help_call_id', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
    }
}
