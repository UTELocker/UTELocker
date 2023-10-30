<?php

namespace App\Libs\PaymentGateway\Message;

use App\Classes\PaymentHelper;
use App\Libs\PaymentGateway\IPaymentClient;
use App\Traits\PaymentGateway\ParametersTrait;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest implements IRequest
{
    use ParametersTrait {
        setParameter as traitSetParameter;
    }

    protected IPaymentClient $httpClient;
    protected Request $httpRequest;
    protected IResponse $response;

    /**
     * @throws \Exception
     */
    public function __construct(
        IPaymentClient $httpClient = null,
        Request $httpRequest = null
    ) {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }

    public function initialize(array $parameters = []): static
    {
        $this->parameters = new ParameterBag();

        PaymentHelper::initialize($this, $parameters);

        return $this;
    }

    public function setParameter(string $key, $value): static
    {
        return $this->traitSetParameter($key, $value);
    }

    public function setTestMode(bool $testMode): static
    {
        return $this->setParameter('testMode', $testMode);
    }

    public function getTestMode(): bool
    {
        return $this->getParameter('testMode');
    }

    public function setPaymentMethodId(int $paymentMethodId): static
    {
        return $this->setParameter('paymentMethodId', $paymentMethodId);
    }

    public function getPaymentMethodId(): int
    {
        return $this->getParameter('paymentMethodId') ?? 0;
    }

    /**
     * @throws \Exception
     */
    public function getResponse(): IResponse
    {
        return $this->response;
    }

    public function send(): IResponse
    {
        $data = $this->getData();

        return $this->sendData($data);
    }
}
