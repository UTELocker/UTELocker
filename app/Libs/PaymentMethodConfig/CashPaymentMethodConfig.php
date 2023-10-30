<?php

namespace App\Libs\PaymentMethodConfig;

class CashPaymentMethodConfig extends PaymentMethodConfig
{
    public const CASH_PMC_DETAILS = 'CASH_PMC_DETAILS';

    public function getPublicConfigs(): array
    {
        return [
            self::CASH_PMC_DETAILS => $this->getConfig(self::CASH_PMC_DETAILS) ?? '',
        ];
    }

    public static function getDefaultConfigs(): array
    {
        return [
            self::CASH_PMC_DETAILS => '',
        ];
    }

    public static function getViewPath(): string
    {
        return 'admin.payments.payment-methods.configs.cash';
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
