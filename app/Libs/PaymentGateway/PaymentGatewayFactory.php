<?php

namespace App\Libs\PaymentGateway;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class PaymentGatewayFactory
{
    private array $gateways = [];

    public function all(): array
    {
        return $this->gateways;
    }

    public function replace(array $gateways): static
    {
        $this->gateways = $gateways;

        return $this;
    }

    public function register($className): static
    {
        if (!in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        }

        return $this;
    }

    public function make($class, ClientInterface $httpClient = null, HttpClient $httpRequest = null): IPaymentGateway
    {
        if (!class_exists($class)) {
            throw new \Exception("Payment gateway {$class} not found.");
        }

        return new $class($httpClient, $httpRequest);
    }
}
