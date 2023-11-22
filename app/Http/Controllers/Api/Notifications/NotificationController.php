<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Notifications\UpdateStatusRequest;
use App\Services\Admin\Notifications\NotificationServices;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public ?NotificationServices $notificationService;

    public function __construct(NotificationServices $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function get()
    {
        $userId = user()->id;
        $notifications = $this->notificationService->getOfUser($userId);
        return Reply::successWithData('Get notifications successfully',
            [
                'data' => new NotificationCollection($notifications)
            ]);
    }

    public function updateStatus($id, UpdateStatusRequest $request)
    {
        $res = $this->notificationService->updateStatus($id);
        if ($id == 'all') {
            return Reply::success('Update status notification successfully');
        }
        return Reply::successWithData('Update status notification successfully',
            [
                'data' => new NotificationResource($res)
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->notificationService->add($data);
        return Reply::success('Create notification successfully');
    }

    public function show($id)
    {
        $notification = $this->notificationService->get($id);
        $notificationDetail = $this->notificationService->getNotificationDetail($notification);
        return Reply::successWithData('Get notification successfully',
            [
                'data' => $notificationDetail
            ]);
    }

    public function getAdminNotifications()
    {
        $notifications = $this->notificationService->getOfUser(user()->id, [
            NotificationType::BOOKING,
            NotificationType::PAYMENT,
            NotificationType::SUPER_ADMIN,
            NotificationType::LOCKER_SYSTEM,
            NotificationType::SITE_GROUP,
            NotificationType::REPORT
        ]);
        return Reply::successWithData('Get notifications successfully',
            [
                'data' => $notifications
            ]);
    }
}
