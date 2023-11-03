<?php

namespace App\Libs\PaymentGateway\Support;

class Signature
{
    protected string $hashSecret;
    protected string $hashType;

    public function __construct(string $hashSecret, string $hashType = 'sha512')
    {
        if (!$this->isSupportHashType($hashType)) {
            throw new \InvalidArgumentException('Unsupported hash type');
        }

        $this->hashSecret = $hashSecret;
        $this->hashType = $hashType;
    }

    public function generate(array $data): string
    {
        ksort($data);
        $dataSignature = http_build_query($data);

        return hash_hmac($this->hashType, $dataSignature, $this->hashSecret);
    }

    public function validate(array $data, string $except): bool
    {
        $actual = $this->generate($data);

        return strcasecmp($except, $actual) === 0;
    }

    protected function isSupportHashType(string $type): bool
    {
        return 0 === strcasecmp($type, 'md5')
            || 0 === strcasecmp($type, 'sha512')
            || 0 === strcasecmp($type, 'sha256');
    }
}
