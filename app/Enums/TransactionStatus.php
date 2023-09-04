<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatus extends Enum
{
    const PENDING = 0;
    const SUCCESS = 1;
    const FAILED = 2;
}
