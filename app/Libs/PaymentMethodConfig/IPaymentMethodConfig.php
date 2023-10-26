<?php

namespace App\Libs\PaymentMethodConfig;

interface IPaymentMethodConfig
{
    public function addConfig(string $fieldName, string|array $value): static;
    public function getConfigs(bool $isRestrict = true): array;
    public function getConfig(string $fieldName): mixed;
    public static function getDefaultConfigs(): array;
    public function getPublicConfigs(): array;
    public function getRestrictedConfigs(): array;
    public function build(): string;
}
