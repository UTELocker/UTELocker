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
    const LOCKER_BROKEN = 'lockerBroken';

    public static function getDescription($value): string
    {
        return self::getDescriptions()[$value];
    }

    public static function getDescriptions(): array
    {
        return [
            self::BOOKING => 'Booking',
            self::PAYMENT => 'Payment',
            self::SUPER_ADMIN => 'Super Admin',
            self::LOCKER_SYSTEM => 'Locker System',
            self::SITE_GROUP => 'Site Group',
            self::REPORT => 'Report',
            self::LOCKER_BROKEN => 'Locker Broken',
        ];
    }
}
