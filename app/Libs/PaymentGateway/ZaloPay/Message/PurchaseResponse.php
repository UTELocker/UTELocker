<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\IRequest;
use Illuminate\Support\Arr;

class PurchaseResponse extends BaseResponse
{
    private string $redirectUrl;

    public function __construct(IRequest $request, mixed $data, array $context = null)
    {
        $this->redirectUrl = Arr::get($context, 'order_url');
        parent::__construct($request, $data);
    }

    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
