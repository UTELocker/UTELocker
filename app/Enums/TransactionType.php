<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionType extends Enum
{
    public const DEPOSIT = 0;
    public const WITHDRAW = 1;
    public const TRANSFER = 2;
    public const PAYMENT = 3;
    public const REFUND = 4;
    public const PROMOTION = 5;

    public static function getDescription($value): string
    {
        return match ($value) {
            self::DEPOSIT => 'Deposit',
            self::WITHDRAW => 'Withdraw',
            self::TRANSFER => 'Transfer',
            self::PAYMENT => 'Payment',
            self::REFUND => 'Refund',
            self::PROMOTION => 'Promotion',
        };
    }
}
