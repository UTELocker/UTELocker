<?php

namespace App\Libs\PaymentGateway;

class PaymentGateway
{
    private static PaymentGatewayFactory $factory;

    public static function getFactory(): PaymentGatewayFactory
    {
        if (!isset(self::$factory)) {
            self::$factory = new PaymentGatewayFactory();
        }

        return self::$factory;
    }

    public static function setFactory(PaymentGatewayFactory $factory = null): void
    {
        self::$factory = $factory;
    }

    public static function __callStatic($method, $arguments)
    {
        $factory = self::getFactory();
        return call_user_func_array(array($factory, $method), $arguments);
    }
}
