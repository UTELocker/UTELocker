<?php

namespace App\Libs\PaymentGateway;

use App\Traits\PaymentGateway\ParametersTrait;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPaymentGateway implements IPaymentGateway
{
    use ParametersTrait {
        setParameter as traitSetParameter;
        getParameter as traitGetParameter;
    }

    protected IPaymentClient $httpClient;
    protected Request $httpRequest;

    public function __construct(
        IPaymentClient $httpClient = null,
        Request $httpRequest = null
    ) {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
    }

    public function getParameter(string $key)
    {
        return $this->traitGetParameter($key);
    }

    public function setParameter(string $key, $value): static
    {
        return $this->traitSetParameter($key, $value);
    }

    public function setCurrency($value): static
    {
        return $this->setParameter('currency', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    /**
     * Check if the payment gateway is authorize.
     * @return bool
     */
    public function isAuthorize(): bool
    {
        return method_exists($this, 'authorize');
    }

    /**
     * Check if the payment gateway is purchase.
     * @return bool
     */
    public function isPurchase(): bool
    {
        return method_exists($this, 'purchase');
    }

    /**
     * Check if the payment gateway is complete purchase.
     * @return bool
     */
    public function isCompletePurchase(): bool
    {
        return method_exists($this, 'completePurchase');
    }

    /**
     * Check if the payment gateway is refund.
     * @return bool
     */
    public function isRefund(): bool
    {
        return method_exists($this, 'refund');
    }

    protected function createRequest($class, array $parameters = []): mixed
    {
        $object = new $class($this->httpClient, $this->httpRequest);
        return $object->initialize(array_replace($this->getParameters(), $parameters));
    }

    protected function getDefaultHttpClient(): PaymentClient
    {
        return new PaymentClient();
    }

    protected function getDefaultHttpRequest(): Request
    {
        return HttpRequest::createFromGlobals();
    }
}
