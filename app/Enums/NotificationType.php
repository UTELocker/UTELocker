<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class NotificationType extends Enum
{
    const BOOKING = 'booking';
    const PAYMENT = 'payment';
    const SUPER_ADMIN = 'superAdmin';
    const LOCKER_SYSTEM = 'lockerSystem';
    const SITE_GROUP = 'siteGroup';
    const REPORT = 'report';

    public static function getDescription($value): string
    {
        return self::getDescriptions()[$value];
    }
}
