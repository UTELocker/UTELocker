<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatus extends Enum
{
    const PENDING = 0;
    const SUCCESS = 1;
    const FAILED = 2;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::PENDING => 'Pending',
            self::SUCCESS => 'Success',
            self::FAILED => 'Failed',
        };
    }
}
