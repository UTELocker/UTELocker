<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\IRequest;
use App\Libs\PaymentGateway\Support\Signature;

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

        if (!isset($data['vnp_SecureHash'])) {
            throw new \Exception('Response from VNPay is invalid!');
        }

        $dataSignature = array_filter($data, function ($params) {
            return str_starts_with($params, 'vnp_')
                && $params !== 'vnp_SecureHash'
                && $params !== 'vnp_SecureHashType';
        }, ARRAY_FILTER_USE_KEY);

//        $signature = new Signature(
//            $this->getRequest()->getVnpHashSecret(),
//            $data['vnp_SecureHashType'] ?? 'md5'
//        );
//
//        if (!$signature->validate($dataSignature, $data['vnp_SecureHash'])) {
//            throw new \Exception('Response from VNPay is invalid!');
//        }
    }
}
