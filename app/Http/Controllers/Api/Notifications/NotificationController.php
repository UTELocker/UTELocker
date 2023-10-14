<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Notifications\UpdateStatusRequest;
use App\Services\Admin\Notifications\NotificationServices;
use Illuminate\Http\Request;

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
                'data' => $notifications
            ]);
    }

    public function updateStatus($id, UpdateStatusRequest $request)
    {
        $this->notificationService->updateStatus($id);
        return Reply::success('Update status successfully');
    }
}
