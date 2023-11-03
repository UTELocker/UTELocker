<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\IRequest;
use App\Libs\PaymentGateway\ZaloPay\Message\BaseResponse;

class SignatureResponse extends BaseResponse
{
    /**
     * @throws \Exception
     */
    public function __construct(IRequest $request, mixed $data)
    {
        parent::__construct($request, $data);

        if ($this->isSuccessful()) {
            $this->validateSignature();
        }
    }

    private function validateSignature(): void
    {
        $data = $this->getData();

        if (!isset($data['checksum'])) {
            throw new \Exception('Response from Zalo is invalid!');
        }

        // Handle check sum here
    }
}
