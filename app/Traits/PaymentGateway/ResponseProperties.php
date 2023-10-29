<?php

namespace App\Traits\PaymentGateway;

trait ResponseProperties
{
    protected string $pattern;
    public function __get($name)
    {
        $property = $this->propertyNormalize($name);

        if (isset($this->data[$property])) {
            return $this->data[$property];
        } else {
            trigger_error(sprintf('Undefined property: %s::$%s', static::class, $name), E_USER_NOTICE);
            return null;
        }
    }

    public function __set($name, $value)
    {
        $property = $this->propertyNormalize($name);

        if (isset($this->data[$property])) {
            $this->data[$property] = $value;
        } else {
            trigger_error(sprintf('Undefined property: %s::$%s', static::class, $name), E_USER_NOTICE);
        }
    }

    abstract protected function propertyNormalize(string $name): string;
}
