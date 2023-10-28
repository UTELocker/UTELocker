<?php

namespace App\Libs\PaymentGateway\Message;

interface IMessage
{
    public function getData(): mixed;
}
