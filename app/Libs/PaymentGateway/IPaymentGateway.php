<?php

namespace App\Libs\PaymentGateway;

interface IPaymentGateway
{
    public function getName();
    public function getShortName();
    public function getDefaultParameters();
    public function initialize(array $parameters = []);
    public function getParameters();
}
