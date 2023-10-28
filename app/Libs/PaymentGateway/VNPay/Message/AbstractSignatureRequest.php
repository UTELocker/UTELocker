<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\AbstractRequest;
use App\Libs\PaymentGateway\Message\IResponse;

class AbstractSignatureRequest extends AbstractRequest
{
    /**
     * @throws \Exception
     */
    public function initialize(array $parameters = []): static
    {
        parent::initialize($this->normalizeParameters($parameters));
        return $this;
    }

    public function getData(): mixed
    {
        call_user_func_array(
            [$this, 'validate'],
            $this->getSignatureParameters()
        );

        $parameters = $this->getParameters();

    }

    public function sendData(mixed $data): IResponse
    {
        // TODO: Implement sendData() method.
    }
}
