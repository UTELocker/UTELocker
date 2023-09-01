<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LockerStatus extends Enum
{
    public const AVAILABLE = 0;
    public const IN_USE = 1;
    public const UNDER_MAINTENANCE = 2;
    public const BROKEN = 3;
}
