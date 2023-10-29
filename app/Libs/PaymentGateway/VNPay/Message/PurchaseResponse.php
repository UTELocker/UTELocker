<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\IRequest;

class PurchaseResponse extends BaseResponse
{
    private string $redirectUrl;

    public function __construct(IRequest $request, mixed $data, string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
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

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
