<?php

namespace App\Libs\PaymentMethodConfig;

class CashPaymentMethodConfig extends PaymentMethodConfig
{
    public const CASH_PMC_DETAILS = 'CASH_PMC_DETAILS';

    public function addDetails(string $value): static
    {
        return $this->addConfig(self::CASH_PMC_DETAILS, $value);
    }

    public function getConfigs(): array
    {
        return [
            self::CASH_PMC_DETAILS => $this->getConfig(self::CASH_PMC_DETAILS) ?? '',
        ];
    }
}
