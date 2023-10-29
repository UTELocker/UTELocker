<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\AbstractRequest;
use App\Libs\PaymentGateway\Message\IResponse;
use App\Traits\PaymentGateway\RequestEndpoint;
use App\Traits\PaymentGateway\RequestSignature;
use App\Traits\PaymentGateway\VNPay\ParametersTrait;

abstract class AbstractSignatureRequest extends AbstractRequest
{
    use RequestSignature;
    use ParametersTrait;
    use RequestEndpoint;

    /**
     * @throws \Exception
     */
    public function initialize(array $parameters = []): static
    {
        parent::initialize($this->normalizeParameters($parameters));

        $this->setVnpIpAddr($this->getVnpIpAddr() ?? $this->httpRequest->getClientIp());
        $this->setVnpCreateDate($this->getVnpCreateDate() ?? date('YmdHis'));
        $this->setVnpExpireDate(
            $this->getVnpExpireDate()
                ?? date('YmdHis', strtotime('+15 minutes', strtotime($this->getVnpCreateDate())))
        );
        $this->setVnpVersion('2.1.0');
        $this->setTestMode(true);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function getData(): array
    {
        $this->validate(...$this->getSignatureParameters());

        $parameters = $this->getParameters();

        $parameters['vnp_SecureHash'] = $this->generateSignature();

        unset($parameters['vnp_HashSecret'], $parameters['testMode']);

        return $parameters;
    }

    protected function getHashSecret(): string
    {
        return $this->getVnpHashSecret();
    }

    public function getSecureHashType(): ?string
    {
        return $this->getParameter('vnp_SecureHashType');
    }

    public function setVnpIpAddr(string $ipAddress)
    {
        return $this->setClientIp($ipAddress);
    }

    public function getVnpIpAddr(): ?string
    {
        return $this->getClientIp();
    }

    public function setClientIp(string $clientIp)
    {
        return $this->setParameter('vnp_IpAddr', $clientIp);
    }

    public function getClientIp(): ?string
    {
        return $this->getParameter('vnp_IpAddr');
    }

    public function setVnpCreateDate(string $data)
    {
        return $this->setParameter('vnp_CreateDate', $data);
    }

    public function getVnpCreateDate(): ?string
    {
        return $this->getParameter('vnp_CreateDate');
    }

    public function setVnpTxnRef(string $ref)
    {
        return $this->setTransactionId($ref);
    }

    public function getVnpTxnRef(): ?string
    {
        return $this->getTransactionId();
    }

    public function getTransactionId(): ?string
    {
        return $this->getParameter('vnp_TxnRef');
    }

    public function setTransactionId(string $id)
    {
        return $this->setParameter('vnp_TxnRef', $id);
    }

    public function getVnpOrderInfo(): ?string
    {
        return $this->getParameter('vnp_OrderInfo');
    }

    public function setVnpOrderInfo(string $info)
    {
        return $this->setParameter('vnp_OrderInfo', $info);
    }

    public function setVnpExpireDate(string $param)
    {
        return $this->setParameter('vnp_ExpireDate', $param);
    }

    public function getVnpExpireDate(): ?string
    {
        return $this->getParameter('vnp_ExpireDate');
    }
}
