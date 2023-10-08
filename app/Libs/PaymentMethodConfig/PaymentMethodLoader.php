<?php

namespace App\Libs\PaymentMethodConfig;

use App\Enums\PaymentMethodType;
use Illuminate\Support\Arr;

class PaymentMethodLoader
{
    public const PAYMENT_LOADERS = [
        PaymentMethodType::CASH => CashPaymentMethodConfig::class,
        PaymentMethodType::BANK_TRANSFER => BankTransferPaymentMethodConfig::class
    ];

    public static function load(string $paymentMethodType, array|string $config = []): IPaymentMethodConfig
    {
        $paymentMethodConfig = Arr::get(self::PAYMENT_LOADERS, $paymentMethodType);
        return new $paymentMethodConfig($config);
    }

    public static function getFields(string $paymentMethodType): array
    {
        $paymentMethodConfig = Arr::get(self::PAYMENT_LOADERS, $paymentMethodType);
        return $paymentMethodConfig::getDefaultConfigs();
    }

    public static function getConfigFromInputs(array $inputs): array
    {
        $type = $inputs['type'];
        $fields = self::getFields($type);
        $configs = [];
        foreach ($fields as $field => $value) {
            $configs[$field] = $inputs[$field] ?? $value;
        }

        return $configs;
    }
}
