<?php

namespace App\Libs\PaymentGateway\VNPay\Message;

use App\Libs\PaymentGateway\Message\IResponse;

class PurchaseRequest extends AbstractSignatureRequest
{
    /**
     * {@inheritdoc}
     */
    protected string $productionEndpoint = 'https://pay.vnpay.vn/vpcpay.html';

    /**
     * {@inheritdoc}
     */
    protected string $testEndpoint = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

    public function initialize(array $parameters = []): static
    {
        parent::initialize($parameters);
        $this->setParameter('vnp_Command', 'pay');
        $this->setVnpLocale($this->getVnpLocale() ?? 'vn');
        $this->setVnpCurrCode($this->getVnpCurrCode() ?? 'VND');
        $this->setVnpOrderInfo($this->getVnpOrderInfo() ?? 'Deposit to account');
        $this->setVnpOrderType($this->getVnpOrderType() ?? 'deposit');
        $this->setVnpTxnRef($this->getVnpTxnRef() ?? "VNPay-{$this->getVnpCreateDate()}");

        return $this;
    }

    public function sendData(mixed $data): IResponse
    {
        $query = http_build_query($data);
        $redirectUrl = $this->getEndpoint().'?'.$query;

        return $this->response = new PurchaseResponse($this, $data, $redirectUrl);
    }

    protected function getSignatureParameters(): array
    {
        $parameters = [
            'vnp_CreateDate',
            'vnp_ExpireDate',
            'vnp_IpAddr',
            'vnp_ReturnUrl',
            'vnp_Amount',
            'vnp_OrderType',
            'vnp_OrderInfo',
            'vnp_TxnRef',
            'vnp_CurrCode',
            'vnp_Locale',
            'vnp_TmnCode',
            'vnp_Command',
            'vnp_Version'
        ];

        if ($this->getVnpBankCode()) {
            $parameters[] = 'vnp_BankCode';
        }

        return $parameters;
    }

    public function getVnpBankCode()
    {
        return $this->getParameter('vnp_BankCode');
    }

    public function setVnpBankCode($value)
    {
        return $this->setParameter('vnp_BankCode', $value);
    }

    public function setVnpLocale(string $locale)
    {
        return $this->setParameter('vnp_Locale', $locale);
    }

    public function getVnpLocale()
    {
        return $this->getParameter('vnp_Locale');
    }

    public function setVnpCurrCode(string $param)
    {
        return $this->setCurrency($param);
    }

    public function setCurrency(string $param)
    {
        return $this->setParameter('vnp_CurrCode', $param);
    }

    public function getVnpCurrCode()
    {
        return $this->getCurrency();
    }

    public function getCurrency()
    {
        return $this->getParameter('vnp_CurrCode');
    }

    public function getVnpReturnUrl()
    {
        $this->getReturnUrl();
    }

    public function setVnpReturnUrl(string $url)
    {
        return $this->setReturnUrl($url);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('vnp_ReturnUrl');
    }

    public function setReturnUrl(string $url)
    {
        return $this->setParameter('vnp_ReturnUrl', $url);
    }

    public function setAmount($amount)
    {
        return $this->setParameter('vnp_Amount', $amount * 100);
    }

    public function getAmount()
    {
        return $this->getParameter('vnp_Amount');
    }

    public function setVnpAmount($amount)
    {
        return $this->setAmount($amount);
    }

    public function getVnpAmount()
    {
        return $this->getAmount();
    }

    public function setVnpOrderType(string $type)
    {
        return $this->setParameter('vnp_OrderType', $type);
    }

    public function getVnpOrderType()
    {
        return $this->getParameter('vnp_OrderType');
    }
}
