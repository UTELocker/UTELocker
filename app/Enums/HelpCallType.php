<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HelpCallType extends Enum
{
    public const BOOKING = 3;
    public const LOCKER = 1;
    public const LOCKER_SLOT = 2;
    public const PAYMENT = 4;

    public static function getAll (): array
    {
        return [
            self::BOOKING,
            self::LOCKER,
            self::LOCKER_SLOT,
            self::PAYMENT
        ];
    }

    public static function getTable (): array
    {
        return [
            self::BOOKING => 'bookings',
            self::LOCKER => 'lockers',
            self::LOCKER_SLOT => 'locker_slots',
            self::PAYMENT => 'payments'
        ];
    }

}
