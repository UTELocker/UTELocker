<?php

namespace App\Libs\PaymentMethodConfig;

class BankTransferPaymentMethodConfig extends PaymentMethodConfig
{
    public const BANK_TRANSFER_PMC_DETAILS = 'BANK_TRANSFER_PMC_DETAILS';
    public const BANK_TRANSFER_PMC_QR_CODE = 'BANK_TRANSFER_PMC_QR_CODE';

    public static function getDefaultConfigs(): array
    {
        return [
            self::BANK_TRANSFER_PMC_DETAILS => '',
            self::BANK_TRANSFER_PMC_QR_CODE => '',
        ];
    }

    public function getPublicConfigs(): array
    {
        return [
            self::BANK_TRANSFER_PMC_DETAILS => $this->getConfig(self::BANK_TRANSFER_PMC_DETAILS) ?? '',
            self::BANK_TRANSFER_PMC_QR_CODE => $this->getConfig(self::BANK_TRANSFER_PMC_QR_CODE) ?? '',
        ];
    }

    public static function getViewPath(): string
    {
        return 'admin.payments.payment-methods.configs.bank-transfer';
    }
}
