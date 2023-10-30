<?php

namespace App\Libs\PaymentMethodConfig;

use App\Libs\PaymentGateway\VNPay\PaymentGateway;

class VNPayPaymentMethodConfig extends PaymentMethodConfig
{
    public const TERMINAL_ID = 'vnp_TmnCode';
    public const SECRET_KEY = 'vnp_HashSecret';

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

    public static function getGateway(): string
    {
        return PaymentGateway::class;
    }

    public static function getAmountFieldName(): string
    {
        return "vnp_Amount";
    }
}
