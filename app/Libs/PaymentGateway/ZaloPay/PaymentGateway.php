<?php

namespace App\Libs\PaymentGateway\ZaloPay;

use App\Libs\PaymentGateway\AbstractPaymentGateway;
use App\Libs\PaymentGateway\ZaloPay\Message\IncomingRequest;
use App\Libs\PaymentGateway\ZaloPay\Message\PurchaseRequest;
use App\Traits\PaymentGateway\ZaloPay\ParametersTrait;

class PaymentGateway extends AbstractPaymentGateway
{
    use ParametersTrait;

    public function getName(): string
    {
        return 'ZaloPay';
    }

    public function getShortName(): string
    {
        return 'zalopay';
    }

    public function getDefaultParameters(): array
    {
        return [];
    }

    public function initialize(array $parameters = []): static
    {
        $parameters = array_merge($this->getDefaultParameters(), $parameters);

        return parent::initialize(
            $this->normalizeParameters($parameters)
        );
    }

    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = [])
    {
        return $this->createRequest(IncomingRequest::class, $options);
    }
}
