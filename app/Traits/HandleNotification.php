<?php

namespace App\Traits;

use App\Classes\CommonConstant;
use App\Enums\NotificationType;
use App\Events\NotificationProcessed;
use App\Models\Notification;

trait HandleNotification {

    public function sendNotifcaion(
        $type,
        $content,
        $owner_id,
        $client_id,
        $parentTable = null,
        $parentId = null,
        $options = [],
    ){
        $notification = new Notification();
        $notification->parent_table = $parentTable;
        $notification->parent_id = $parentId;
        $notification->type = $type;
        $notification->content = $content;
        $notification->client_id = $client_id;
        $notification->owner_id = $owner_id;
        $notification->save();

        event(new NotificationProcessed($notification, $options));
    }

    public function changeStatusNotification($id, $status = true){
        $notification = Notification::find($id);
        $notification->status = $status ? CommonConstant::DATABASE_YES : CommonConstant::DATABASE_NO;
        $notification->save();
    }

}
