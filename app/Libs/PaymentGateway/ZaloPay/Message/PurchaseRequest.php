<?php

namespace App\Libs\PaymentGateway\ZaloPay\Message;

use App\Libs\PaymentGateway\Message\IResponse;
use App\Libs\PaymentGateway\Support\PaymentHelper;
use App\Libs\PaymentGateway\ZaloPay\Message\PurchaseResponse;

class PurchaseRequest extends AbstractSignatureRequest
{
    protected string $productionEndpoint = 'https://openapi.zalopay.vn/v2/create';
    protected string $testEndpoint = 'https://sb-openapi.zalopay.vn/v2/create';

    public function initialize(array $parameters = []): static
    {
        parent::initialize($parameters);
        $this->setBankCode('');
        $this->setDescription($this->getDescription() ?? 'Deposit to account');
        $this->setAppTime($this->getAppTime() ?? PaymentHelper::getTimeStamp());
        $this->setAppTransId($this->getAppTransId() ?? $this->generateTransactionId());
        $this->setAppUser($this->getAppUser() ?? 'demo');
        $this->setItem($this->getItem() ?? '[]');

        $embedData = [
            'preferred_payment_method' => [],
        ];

        $this->setEmbedData($this->getEmbedData() ?? json_encode($embedData));
        return $this;
    }

    public function sendData(mixed $data): IResponse
    {
        $content = http_build_query($data);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $content,
            ],
        ]);

        return $this->response = new PurchaseResponse(
            $this,
            $data,
            json_decode(file_get_contents($this->getEndpoint(), false, $context), true)
        );
    }

    protected function getHashSecret(): string
    {
        return $this->getKey1();
    }

    public function setReturnUrl(string $value): static
    {
        return $this->setParameter('callback_url', $value);
    }

    public function getReturnUrl(): ?string
    {
        return $this->getParameter('callback_url');
    }

    protected function getSignatureParameters(): array
    {
        return [
            'app_id',
            'app_trans_id',
            'app_user',
            'amount',
            'app_time',
            'embed_data',
            'item',
        ];
    }
}
