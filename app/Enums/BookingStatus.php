<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingStatus extends Enum
{
    public const PENDING = 0;
    public const APPROVED = 1;
    public const REJECTED = 2;
    public const CANCELLED = 3;
    public const EXPIRED = 4;
    public const COMPLETED = 5;
    public const LOCKED = 6;
}
