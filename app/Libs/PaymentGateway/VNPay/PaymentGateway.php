<?php

namespace App\Libs\PaymentGateway\VNPay;

use App\Libs\PaymentGateway\AbstractPaymentGateway;
use App\Libs\PaymentGateway\VNPay\Message\IncomingRequest;
use App\Libs\PaymentGateway\VNPay\Message\PurchaseRequest;
use App\Traits\PaymentGateway\VNPay\ParametersTrait;

class PaymentGateway extends AbstractPaymentGateway
{
    use ParametersTrait;

    public function getName(): string
    {
        return 'VNPay';
    }

    public function getShortName(): string
    {
        return 'vnpay';
    }

    public function getDefaultParameters(): array
    {
        return [
            'vnp_Version' => '2.1.0',
        ];
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
