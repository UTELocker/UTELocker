<?php

namespace App\Traits\PaymentGateway\VNPay;

trait ParametersTrait
{
    public function getVnpVersion()
    {
        return $this->getParameter('vnp_Version');
    }

    public function setVnpVersion($value): static
    {
        return $this->setParameter('vnp_Version', $value);
    }

    public function getVnpTmnCode(): ?string
    {
        return $this->getParameter('vnp_TmnCode');
    }

    public function setVnpTmnCode($value): static
    {
        return $this->setParameter('vnp_TmnCode', $value);
    }

    public function getVnpHashSecret(): ?string
    {
        return $this->getParameter('vnp_HashSecret');
    }

    public function setVnpHashSecret(?string $secret): static
    {
        return $this->setParameter('vnp_HashSecret', $secret);
    }
}
