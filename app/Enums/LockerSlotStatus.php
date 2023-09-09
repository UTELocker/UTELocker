<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LockerSlotStatus extends Enum
{
    public const AVAILABLE = 0;
    public const BOOKED = 1;
    public const LOCKED = 2;

    public static function getDescription($value): string
    {
        return self::getDescriptions()[$value];
    }

    public static function getDescriptions($exclude = []): array
    {
        $descriptions = [
            self::AVAILABLE => 'Available',
            self::BOOKED => 'Booked',
            self::LOCKED => 'Locked',
        ];

        if (count($exclude) > 0) {
            foreach ($exclude as $value) {
                unset($descriptions[$value]);
            }
        }

        return $descriptions;
    }
}
