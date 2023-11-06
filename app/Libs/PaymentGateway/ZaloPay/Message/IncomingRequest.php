<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\AbstractRequest;
use App\Libs\PaymentGateway\Message\IResponse;
use App\Libs\PaymentGateway\ZaloPay\Message\SignatureResponse;
use App\Traits\PaymentGateway\ZaloPay\ParametersTrait;

class IncomingRequest extends AbstractRequest
{
    use ParametersTrait;

    /**
     * @throws \Exception
     */
    public function getData(): mixed
    {
        $parameters = $this->getIncomingParameters();
        $this->validate(...array_keys($parameters));

        return $parameters;
    }

    /**
     * @throws \Exception
     */
    public function sendData(mixed $data): IResponse
    {
        return $this->response = new SignatureResponse($this, $data);
    }

    public function initialize(array $parameters = []): static
    {
        parent::initialize(
            $this->normalizeParameters($parameters)
        );

        foreach ($this->getIncomingParameters() as $key => $value) {
            $this->setParameter($key, $value);
        }

        return $this;
    }

    private function getIncomingParameters(): array
    {
        return $this->httpRequest->query->all();
    }
}
