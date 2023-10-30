<?php

namespace App\Http\Controllers\Api\Payments;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payments\DepositRequest;
use App\Libs\PaymentGateway\PaymentGateway;
use App\Libs\PaymentMethodConfig\PaymentMethodLoader;
use App\Services\Admin\Payments\PaymentMethodService;
use App\Services\Wallets\WalletService;
use Illuminate\Http\Request;
use App\Libs\PaymentGateway\VNPay\PaymentGateway as VNPayPaymentGateway;
use Illuminate\Support\Arr;

class PaymentController extends Controller
{
    private WalletService $walletService;
    private PaymentMethodService $paymentMethodService;

    public function __construct(WalletService $walletService, PaymentMethodService $paymentMethodService)
    {
        parent::__construct();
        $this->walletService = $walletService;
        $this->paymentMethodService = $paymentMethodService;
    }

    public function getWallet(Request $request)
    {
        $user = $request->user();
        $wallet = $this->walletService->getWalletByUserId($user);

        return Reply::successWithData(
            'Get wallet successfully',
            [
                'data' => $wallet,
            ]
        );
    }

    public function deposit(DepositRequest $request)
    {
        $paymentMethod = $this->paymentMethodService->get(Arr::get($request->all(), 'payment_method_id'));
        $paymentMethodConfig = PaymentMethodLoader::load($paymentMethod->type, $paymentMethod->config);

        $gateway = PaymentGateway::make($paymentMethodConfig->getGateway())
            ->initialize($paymentMethodConfig->getRestrictedConfigs());

        $response = $gateway->purchase([
            $paymentMethodConfig->getAmountFieldName() => Arr::get($request->all(), 'amount'),
        ])->send();

        return Reply::successWithData(
            'Get URL successfully',
            [
                'redirectUrl' => $response->getRedirectUrl(),
            ]
        );
    }

    public function depositCallback(Request $request)
    {
        $gateway = PaymentGateway::make(VNPayPaymentGateway::class);

        $response = $gateway->completePurchase()->send();
        if ($response->isSuccessful()) {
            dd($response->getData());
        } else {
            dd($response->getMessage());
        }
    }
}
