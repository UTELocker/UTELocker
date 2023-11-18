<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use App\Services\Admin\Notifications\NotificationServices;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use App\Classes\Reply;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{

    public ?NotificationServices $notificationService;

    public function __construct(NotificationServices $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
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
                'data' => new NotificationCollection($notifications)
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->notificationService->updateStatus($id);
        return Reply::success('Update notification successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
