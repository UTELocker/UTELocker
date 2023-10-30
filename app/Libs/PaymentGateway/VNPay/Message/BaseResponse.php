<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\AbstractResponse;
use App\Traits\PaymentGateway\ResponseProperties;

class BaseResponse extends AbstractResponse
{
    use ResponseProperties;

    public function isSuccessful(): bool
    {
        return '00' === $this->getCode();
    }

    public function isCancelled(): bool
    {
        return '24' === $this->getCode();
    }

    public function getCode(): ?string
    {
        return $this->data['vnp_ResponseCode'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['vnp_TxnRef'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['vnp_TransactionNo'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['vnp_Message'] ?? null;
    }

    public function getAmount(): ?int
    {
        return $this->data['vnp_Amount'] ? (int) $this->data['vnp_Amount'] / 100 : null;
    }

    protected function propertyNormalize(string $name): string
    {
        if ((str_starts_with($name, 'vnp')) && !str_contains($name, '_')) {
            return 'vnp_' . $name;
        }

        return $name;
    }
}
