<?php

namespace App\Traits\PaymentGateway;

use App\Classes\PaymentHelper;
use Symfony\Component\HttpFoundation\ParameterBag;

trait ParametersTrait
{
    protected ParameterBag $parameters;

    public function getParameter(string $key)
    {
        return $this->parameters->get($key);
    }

    public function setParameter(string $key, $value): static
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters->all();
    }

    public function initialize(array $parameters = []): static
    {
        $this->parameters = new ParameterBag();
        PaymentHelper::initialize($this, $parameters);
        return $this;
    }

    public function normalizeParameters(array $parameters): array
    {
        $normalized = [];
        foreach ($parameters as $key => $value) {
            $normalized[PaymentHelper::camelCase($key)] = $value;
        }
        return $normalized;
    }

    /**
     * @throws \Exception
     */
    public function validate(...$args): void
    {
        foreach ($args as $key) {
            $value = $this->parameters->get($key);
            if (! isset($value)) {
                throw new \Exception("The $key parameter is required");
            }
        }
    }
}
