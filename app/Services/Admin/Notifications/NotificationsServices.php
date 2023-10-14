<?php

namespace App\Services\Admin\Notifications;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Models\Notification;
use App\Services\BaseService;

class NotificationsServices extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Notification());
    }

    public function updateStatus($id)
    {
        $this->model = $this->get($id);
        $this->model->status = CommonConstant::DATABASE_YES;
        $this->model->save();
    }

    protected function formatInputData(&$inputs)
    {
        $inputs['client_id'] = $inputs['client_id'] ?? auth()->user()->client_id;
        $inputs['status'] = $inputs['status'] ?? CommonConstant::DATABASE_NO;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'parent_table', $inputs);
        Common::assignField($this->model, 'parent_id', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'owner_id', $inputs);
        Common::assignField($this->model, 'content', $inputs);
        Common::assignField($this->model, 'status', $inputs);

    }

    public function getOfUser($userId)
    {
        return $this->model
            ->where('owner_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function get($id)
    {
        return $this->model->findOrfail($id);
    }
}
