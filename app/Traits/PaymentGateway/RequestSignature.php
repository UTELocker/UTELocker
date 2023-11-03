<?php

namespace App\Traits\PaymentGateway;

use App\Libs\PaymentGateway\Support\Signature;

trait RequestSignature
{
    protected function generateSignature(string $hashType = 'sha512', string $implodeChar = null): string
    {
        $data = [];
        $signature = new Signature($this->getHashSecret(), $hashType);

        foreach ($this->getSignatureParameters() as $key) {
            $data[$key] = $this->getParameter($key);
        }

        if ($implodeChar) {
            $data = [implode($implodeChar, $data)];
        }

        return $signature->generate($data);
    }

    abstract protected function getHashSecret(): string;
    abstract protected function getSignatureParameters(): array;
}
