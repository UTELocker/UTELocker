<?php

namespace App\Libs\PaymentGateway\Message;

interface IResponse extends IMessage
{
    public function getRequest(): IRequest;
    public function isSuccessful(): bool;
    public function isRedirect(): bool;
    public function isCancelled(): bool;
    public function getMessage(): ?string;
    public function getCode(): ?string;
    public function getTransactionReference(): ?string;
}
