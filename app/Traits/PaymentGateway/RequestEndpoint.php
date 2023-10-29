<?php

namespace App\Traits\PaymentGateway;

trait RequestEndpoint
{
    protected $productionEndpoint;
    protected $testEndpoint;

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->productionEndpoint;
    }
}
