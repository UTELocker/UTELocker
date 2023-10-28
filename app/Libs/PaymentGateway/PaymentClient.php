<?php

namespace App\Libs\PaymentGateway;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Client\ClientExceptionInterface;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Client\ClientInterface;

class PaymentClient implements IPaymentClient
{
    private ClientInterface $httpClient;
    private RequestFactory $requestFactory;

    public function __construct(
        ClientInterface $httpClient = null,
        RequestFactory $requestFactory = null
    ) {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function request(
        string $method,
        UriInterface|string $uri,
        array $headers = [],
        StreamInterface|string $body = null,
        string $version = self::VERSION
    ): ResponseInterface
    {
        $request = $this->requestFactory->createRequest(
            $method,
            $uri,
            $headers,
            $body,
            $version
        );

        return $this->sendRequest($request);
    }

    /**
     * @throws ClientExceptionInterface
     */
    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
