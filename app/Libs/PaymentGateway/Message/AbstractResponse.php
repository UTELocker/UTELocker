<?php

namespace App\Libs\PaymentGateway\Message;

abstract class AbstractResponse implements IResponse
{
    protected IRequest $request;
    protected mixed $data;

    public function __construct(IRequest $request, mixed $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function getRequest(): IRequest
    {
        return $this->request;
    }

    public function isRedirect(): bool
    {
        return false;
    }

    public function isCancelled(): bool
    {
        return false;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getTransactionReference(): ?string
    {
        return null;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): array
    {
        return [];
    }
}
