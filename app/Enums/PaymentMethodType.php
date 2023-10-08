<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethodType extends Enum
{
    const CASH = 'cash';
    const BANK_TRANSFER = 'bank_transfer';
    const PAYPAL = 'paypal';
    const ZALOPAY = 'zalopay';

    public static function getDescription($value): string
    {
        return self::getDescriptions()[$value];
    }

    public static function getDescriptions($exclude = []): array
    {
        $descriptions = [
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::PAYPAL => 'Paypal',
            self::ZALOPAY => 'Zalopay',
        ];

        if (count($exclude) > 0) {
            foreach ($exclude as $value) {
                unset($descriptions[$value]);
            }
        }

        return $descriptions;
    }

    public static function getNotAvailableTypes(): array
    {
        return [
            self::PAYPAL,
        ];
    }
}
