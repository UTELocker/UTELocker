<?php

namespace App\Traits;

use App\Classes\CommonConstant;
use App\Enums\NotificationType;
use App\Events\NotificationProcessed;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

trait HandleNotification {

    public function sendNotifcaion(
        $type,
        $content,
        $ownerId,
        $clientId,
        $parentTable = null,
        $parentId = null,
        $options = [],
    ){
        DB::transaction(function () use (
            $type,
            $content,
            $ownerId,
            $clientId,
            $parentTable,
            $parentId,
            $options
        ) {
            $notification = new Notification();
            $notification->type = $type;
            $notification->content = $content;
            $notification->owner_id = $ownerId;
            $notification->client_id = $clientId;
            $notification->parent_table = $parentTable;
            $notification->parent_id = $parentId;
            $notification->save();
            event(new NotificationProcessed($notification, $options));
            return true;
        });
        return false;
    }

    public function changeStatusNotification($id, $status = true){
        $notification = Notification::find($id);
        $notification->status = $status ? CommonConstant::DATABASE_YES : CommonConstant::DATABASE_NO;
        $notification->save();
    }

}
