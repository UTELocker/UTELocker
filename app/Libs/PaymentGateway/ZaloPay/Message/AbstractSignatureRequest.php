<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\AbstractRequest;
use App\Traits\PaymentGateway\RequestEndpoint;
use App\Traits\PaymentGateway\RequestSignature;
use App\Traits\PaymentGateway\ZaloPay\ParametersTrait;

abstract class AbstractSignatureRequest extends AbstractRequest
{
    use RequestSignature;
    use ParametersTrait;
    use RequestEndpoint;

    public function initialize(array $parameters = []): static
    {
        parent::initialize($this->normalizeParameters($parameters));
        $this->setTestMode(true);
        return $this;
    }

    public function getData(): mixed
    {
        $embedData = [
            'preferred_payment_method' => [],
            'redirecturl' => $this->getReturnUrl(),
        ];
        $this->setEmbedData(json_encode($embedData));
        $this->validate(...$this->getSignatureParameters());
        $parameters = $this->getParameters();
        $data = [];
        foreach ($this->getSignatureParameters() as $key) {
            $data[$key] = $this->getParameter($key);
        }
        $parameters['mac'] = hash_hmac('sha256', implode('|', $data), $this->getHashSecret());
        unset(
            $parameters['testMode'],
            $parameters['key2'],
        );
        return $parameters;
    }

    public function setMac(string $mac): static
    {
        return $this->setParameter('mac', $mac);
    }

    public function getMac(): ?string
    {
        return $this->getParameter('mac');
    }

    protected function generateTransactionId(): string
    {
        return date("ymd") . '_' . time();
    }
}
