<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Notifications\UpdateStatusRequest;
use App\Services\Admin\Notifications\NotificationsServices;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public ?NotificationsServices $notificationsService;

    public function __construct(NotificationsServices $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function get()
    {
        $userId = \auth()->user()->id;
        $notifications = $this->notificationsService->getOfUser($userId);
        return Reply::successWithData('Get notifications successfully',
            [
                'data' => $notifications
            ]);
    }

    public function updateStatus($id, UpdateStatusRequest $request)
    {
        $this->notificationsService->updateStatus($id);
        return Reply::success('Update status successfully');
    }
}
