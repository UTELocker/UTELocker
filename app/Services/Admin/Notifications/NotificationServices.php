<?php

namespace App\Services\Admin\Notifications;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Enums\NotificationType;
use App\Models\Notification;
use App\Services\BaseService;
use App\Traits\HandleNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\LockerSlot;
use App\Enums\NotificationParentTable;

class NotificationServices extends BaseService
{
    use HandleNotification;

    public function __construct()
    {
        parent::__construct(new Notification());
    }

    public function updateStatus($id)
    {
        if ($id == 'all') {
            $this->model->where('owner_id', user()->id)
                ->update(['status' => CommonConstant::DATABASE_YES]);
            return;
        }
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

    public function getOfUser($userId, $skipType = [])
    {
        $query = $this->model
            ->where('owner_id', $userId)
            ->orderBy('created_at', 'desc');
        if (!empty($skipType)) {
            $query->whereNotIn('type', $skipType);
        }
        return $query->get();
    }

    public function get($id)
    {
        return $this->model->findOrfail($id);
    }

    public function add($data)
    {
        $this->sendNotification(
            NotificationType::REPORT,
            $data['content'],
            $data['owner_id'],
            $data['client_id'],
        );
    }

    public function getNotificationDetail(Notification $notification)
    {
        $notification->content_detail = NotificationParentTable::queryTable(
            $notification->parent_table,
            $notification->parent_id
        );
        return $notification;
    }
}
