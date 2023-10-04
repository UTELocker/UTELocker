<?php

namespace App\Libs\PaymentMethodConfig;

interface IPaymentMethodConfig
{
    public function addConfig(string $fieldName, string|array $value): static;
    public function getConfigs(): array;
    public function getConfig(string $fieldName): mixed;
    public function build(): string;
}
