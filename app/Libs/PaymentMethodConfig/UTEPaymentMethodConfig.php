<?php

namespace App\Libs\PaymentMethodConfig;

class UTEPaymentMethodConfig extends PaymentMethodConfig
{

    public const UTE_PAY_DETAILS = 'UTE_PAY_DETAILS';

    public function getPublicConfigs(): array
    {
        return [
            self::UTE_PAY_DETAILS => $this->getConfig(self::UTE_PAY_DETAILS) ?? '',
        ];
    }

    public static function getDefaultConfigs(): array
    {
        return [
            self::UTE_PAY_DETAILS => '',
        ];
    }

    public static function getViewPath(): string
    {
        return 'admin.payments.payment-methods.configs.utepay';
    }

    public static function getGateway(): string
    {
        return "";
    }

    public static function getAmountFieldName(): string
    {
        return "amount";
    }
}
