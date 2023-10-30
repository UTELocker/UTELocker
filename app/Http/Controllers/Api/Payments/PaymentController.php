<?php

namespace App\Http\Controllers\Api\Payments;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Libs\PaymentGateway\PaymentGateway;
use App\Services\Wallets\WalletService;
use Illuminate\Http\Request;
use App\Libs\PaymentGateway\VNPay\PaymentGateway as VNPayPaymentGateway;

class PaymentController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
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

    public function deposit(Request $request)
    {
        $gateway = PaymentGateway::make(VNPayPaymentGateway::class)
            ->initialize([
                'vnp_TmnCode' => 'NT6784UT',
                'vnp_HashSecret' => 'DGXLFOFVTXNNBVOYXPJGZOVXABQITIBG',
            ]);

        $response = $gateway->purchase([
            'vnp_Amount' => 10000000,
            'vnp_OrderInfo' => 'Nap tien vao tai khoan',
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => route('portal.wallet.deposit.callback'),
            'vnp_TxnRef' => 'DEMO-' . time(),
        ])->send();

        if ($response->isRedirect()) {
            return Reply::successWithData(
                'Get wallet successfully',
                [
                    'redirectUrl' => $response->getRedirectUrl(),
                ]
            );
        }
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
