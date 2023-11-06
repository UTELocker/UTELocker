<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HelpCallStatus extends Enum
{
    public const PENDING= 0;
    public const ACCEPTED= 1;
    public const REJECTED= 2;
    public const CANCELLED= 3;
    public const DONE= 4;

    public static function getAllStatuses(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED,
            self::CANCELLED,
            self::DONE,
        ];
    }
}
