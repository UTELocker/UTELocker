<?php

namespace App\Libs\PaymentGateway\Message;


interface IRequest extends IMessage
{
    public function initialize(array $parameters = []): static;
    public function getParameters(): array;
    public function getResponse(): IResponse;
    public function send(): IResponse;
    public function sendData(mixed $data): IResponse;
}
