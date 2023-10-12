<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('notification.{type}.{client_id}.{owner_id}', function (
    $user,
    $type,
    $client_id,
    $owner_id
) {
    return $user->id === $owner_id && $user->client_id === $client_id;
});
