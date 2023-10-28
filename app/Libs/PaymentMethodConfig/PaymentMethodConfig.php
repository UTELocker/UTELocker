<?php

namespace App\Libs\PaymentMethodConfig;

abstract class PaymentMethodConfig implements IPaymentMethodConfig
{
    protected array $config = [];

    public function __construct(array|string $config = [])
    {
        if (is_string($config)) {
            $config = json_decode($config, true);
        }

        $this->config = $config;
        return $this;
    }

    public function getConfigs(bool $isRestrict = true): array
    {
        if ($isRestrict) {
            return array_merge($this->getPublicConfigs(), $this->getRestrictedConfigs());
        }

        return $this->getPublicConfigs();
    }

    public function addConfig(string $fieldName, string|array $value): static
    {
        $this->config[$fieldName] = $value;
        return $this;
    }

    public function getPublicConfigs(): array
    {
        return $this->config;
    }

    public function getRestrictedConfigs(): array
    {
        return [];
    }

    public function getConfig(string $fieldName): mixed
    {
        return $this->config[$fieldName] ?? null;
    }

    abstract public static function getViewPath(): string;

    abstract public static function getDefaultConfigs(): array;

    public function build(): string
    {
        return json_encode($this->config);
    }
}
