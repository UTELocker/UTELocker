<?php

namespace App\Libs\PaymentGateway;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

interface IPaymentClient
{
    public const VERSION = '1.1';
    public function request(
        string $method,
        UriInterface|string $uri,
        array $headers = [],
        string|StreamInterface $body = null,
        string $version = self::VERSION
    );
}
