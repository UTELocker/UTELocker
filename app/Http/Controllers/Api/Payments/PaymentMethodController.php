<?php

namespace App\Http\Controllers\Api\Payments;

use App\Classes\Reply;
use App\Http\Controllers\ApiController;
use App\Libs\PaymentMethodConfig\PaymentMethodLoader;
use App\Services\Admin\Payments\PaymentMethodService;

class PaymentMethodController extends ApiController
{
    private PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index()
    {
        $paymentMethods = $this->paymentMethodService->getAll();

        return Reply::successWithData(
            "Get payment methods successfully",
            [
                'data' => $paymentMethods,
            ]
        );
    }

    public function show($id)
    {
        $paymentMethod = $this->paymentMethodService->get($id);
        $paymentMethodConfig = PaymentMethodLoader::load($paymentMethod->type, $paymentMethod->config);
        unset($paymentMethod->config);
        return Reply::successWithData(
            "Get payment method successfully",
            [
                'data' => $paymentMethod,
                'config' => $paymentMethodConfig->getConfigs(),
            ]
        );
    }
}
