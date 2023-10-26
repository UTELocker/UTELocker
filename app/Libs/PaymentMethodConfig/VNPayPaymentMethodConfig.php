<?php

namespace App\Libs\PaymentMethodConfig;

class VNPayPaymentMethodConfig extends PaymentMethodConfig
{
    public const TERMINAL_ID = 'TERMINAL_ID';
    public const SECRET_KEY = 'SECRET_KEY';

    public function getPublicConfigs(): array
    {
        return [];
    }

    public function getRestrictedConfigs(): array
    {
        return [
            self::TERMINAL_ID => $this->getConfig(self::TERMINAL_ID) ?? '',
            self::SECRET_KEY => $this->getConfig(self::SECRET_KEY) ?? '',
        ];
    }

    public static function getDefaultConfigs(): array
    {
        return [
            self::TERMINAL_ID => '',
            self::SECRET_KEY => '',
        ];
    }

    public static function getViewPath(): string
    {
        return 'admin.payments.payment-methods.configs.vnpay';
    }
}
