<?php

namespace App\Libs\PaymentGateway\Message;

use App\Classes\PaymentHelper;
use App\Traits\PaymentGateway\ParametersTrait;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class AbstractRequest implements IRequest
{
    use ParametersTrait {
        setParameter as traitSetParameter;
    }

    protected ClientInterface $httpClient;
    protected Request $httpRequest;
    protected IResponse $response;

    /**
     * @throws \Exception
     */
    public function __construct(
        ClientInterface $httpClient = null,
        Request $httpRequest = null
    ) {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }

    public function initialize(array $parameters = []): static
    {
        if ($this->response !== null) {
            throw new \Exception('Request cannot be modified after it has been sent!');
        }

        $this->parameters = new ParameterBag();

        PaymentHelper::initialize($this, $parameters);

        return $this;
    }

    public function setParameter(string $key, $value): static
    {
        if ($this->response !== null) {
            throw new \Exception('Request cannot be modified after it has been sent!');
        }

        return $this->traitSetParameter($key, $value);
    }

    /**
     * @throws \Exception
     */
    public function getResponse(): IResponse
    {
        if ($this->response === null) {
            throw new \Exception('Request has not been sent yet!');
        }

        return $this->response;
    }

    public function send(): IResponse
    {
        $data = $this->getData();

        return $this->sendData($data);
    }
}
