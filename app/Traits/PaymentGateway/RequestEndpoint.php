<?php

namespace App\Traits\PaymentGateway;

trait RequestEndpoint
{
    protected string $productionEndpoint;
    protected string $testEndpoint;

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->productionEndpoint;
    }
}
