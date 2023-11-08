<?php

namespace App\Observers;

use App\Classes\CommonConstant;
use App\Enums\PaymentMethodType;
use App\Libs\PaymentMethodConfig\UTEPaymentMethodConfig;
use App\Models\Client;
use App\Services\Admin\Payments\PaymentMethodService;
use Illuminate\Support\Facades\Log;

class ClientObserver
{
    private PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        $utepaymentConfig = [
            UTEPaymentMethodConfig::UTE_PAY_DETAILS => '',
        ];
        $utepayment = [
            "code" => "UTE_PAY",
            "name" => "UTEPay",
            "type" => PaymentMethodType::UTEPAY,
            "active" => CommonConstant::DATABASE_YES,
            "config" => json_encode($utepaymentConfig),
            "client_id" => $client->id,
        ];
        $this->paymentMethodService->add($utepayment);
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     */
    public function forceDeleted(Client $client): void
    {
        //
    }
}
