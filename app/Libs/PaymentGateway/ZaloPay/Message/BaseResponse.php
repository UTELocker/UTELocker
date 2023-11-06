<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\AbstractResponse;

class BaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getCode() == '1';
    }

    public function getMessage(): ?string
    {
        return "";
    }

    public function getCode(): ?string
    {
        return $this->data['status'] ?? null;
    }

    public function getAmount(): ?int
    {
        return $this->data['amount'] ? (int) $this->data['amount'] : null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['apptransid'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['apptransid'] ?? null;
    }
}
