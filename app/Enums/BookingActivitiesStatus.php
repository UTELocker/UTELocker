<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BookingActivitiesStatus extends Enum
{
    const NOT_YET = 0;
    const ACTIVE = 1;
    const EXPIRED = 2;
}
