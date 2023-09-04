<?php

use App\Classes\Files;

function getAvatarDefault($image)
{
    if ($image) {
        return Files::getImageUrl(
            $image, 'user-avatar',
            Files::USER_UPLOAD_FOLDER
        );
    } else {
        return asset('images/default/avatarDefault.png');
    }
}

function getLogoDefault($image)
{
    if ($image) {
        return Files::getImageUrl(
            $image, 'client-logo',
            Files::CLIENT_UPLOAD_FOLDER
        );
    } else {
        return asset('images/default/logoDefault.png');
    }
}
