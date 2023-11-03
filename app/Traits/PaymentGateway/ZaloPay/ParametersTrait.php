<?php

namespace App\Traits\PaymentGateway\ZaloPay;

trait ParametersTrait
{
    public function setAppId(string $appId): static
    {
        return $this->setParameter('app_id', $appId);
    }

    public function setKey1(string $key1): static
    {
        return $this->setParameter('key1', $key1);
    }

    public function setKey2(string $key2): static
    {
        return $this->setParameter('key2', $key2);
    }

    public function getKey1(): ?string
    {
        return $this->getParameter('key1');
    }

    public function getKey2(): ?string
    {
        return $this->getParameter('key2');
    }

    public function getAppId(): ?string
    {
        return $this->getParameter('app_id');
    }

    public function setAppTime(string $appTime): static
    {
        return $this->setParameter('app_time', $appTime);
    }

    public function getAppTime(): ?string
    {
        return $this->getParameter('app_time');
    }

    public function setAppTransId(string $appTransId): static
    {
        return $this->setParameter('app_trans_id', $appTransId);
    }

    public function getAppTransId(): ?string
    {
        return $this->getParameter('app_trans_id');
    }

    public function setAppUser(string $appUser): static
    {
        return $this->setParameter('app_user', $appUser);
    }

    public function getAppUser(): ?string
    {
        return $this->getParameter('app_user');
    }

    public function setAmount(int $amount): static
    {
        return $this->setParameter('amount', $amount);
    }

    public function getAmount(): ?int
    {
        return $this->getParameter('amount');
    }

    public function setItem(string $item): static
    {
        return $this->setParameter('item', $item);
    }

    public function getItem(): ?string
    {
        return $this->getParameter('item');
    }

    public function setEmbedData(string $embedData): static
    {
        return $this->setParameter('embed_data', $embedData);
    }

    public function getEmbedData(): ?string
    {
        return $this->getParameter('embed_data');
    }

    public function setBankCode(string $bankCode): static
    {
        return $this->setParameter('bank_code', $bankCode);
    }

    public function getBankCode(): ?string
    {
        return $this->getParameter('bank_code');
    }

    public function setDescription(string $description): static
    {
        return $this->setParameter('description', $description);
    }

    public function getDescription(): ?string
    {
        return $this->getParameter('description');
    }
}
