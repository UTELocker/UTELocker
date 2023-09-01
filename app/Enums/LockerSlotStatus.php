<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LockerSlotStatus extends Enum
{
    public const AVAILABLE = 0;
    public const BOOKED = 1;
    public const LOCKED = 2;
}
