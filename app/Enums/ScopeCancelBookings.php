<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ScopeCancelBookings extends Enum
{
    const LOCKER = 'locker';
    const LOCKER_SLOT = 'locker_slot';
    const USER = 'user';
}
