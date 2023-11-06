<?php

namespace App\Libs\PaymentMethodConfig;

use App\Libs\PaymentGateway\ZaloPay\PaymentGateway;

class ZaloPayPaymentMethodConfig extends PaymentMethodConfig
{
    public const APP_ID = 'app_id';
    public const KEY_1 = 'key1';
    public const KEY_2 = 'key2';

    public function getPublicConfigs(): array
    {
        return [];
    }

    public function getRestrictedConfigs(): array
    {
        return [
            self::APP_ID => $this->getConfig(self::APP_ID) ?? '',
            self::KEY_1 => $this->getConfig(self::KEY_1) ?? '',
            self::KEY_2 => $this->getConfig(self::KEY_2) ?? '',
        ];
    }

    public static function getDefaultConfigs(): array
    {
        return [
            self::APP_ID => '',
            self::KEY_1 => '',
            self::KEY_2 => '',
        ];
    }

    public static function getViewPath(): string
    {
        return 'admin.payments.payment-methods.configs.zalopay';
    }

    public static function getGateway(): string
    {
        return PaymentGateway::class;
    }

    public static function getAmountFieldName(): string
    {
        return "amount";
    }
}
