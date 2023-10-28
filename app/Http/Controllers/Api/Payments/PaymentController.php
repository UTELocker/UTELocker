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
        $user = $request->user();
        $gateway = PaymentGateway::make(VNPayPaymentGateway::class)
            ->initialize([
                'vnp_TmnCode' => '2QXUI4J4',
                'vnp_HashSecret' => 'JZJZQZQZQZQZQZQZQZQZQZQZQZQZQZQZ',
            ]);

        $response = $gateway->purchase([
            'vnp_Amount' => 100000,
            'vnp_BankCode' => 'NCB',
            'vnp_OrderInfo' => 'Nap tien vao tai khoan',
            'vnp_OrderType' => 'topup',
            'vnp_ReturnUrl' => 'http://localhost:8000/api/payments/deposit/callback',
            'vnp_TxnRef' => 'DEMO-' . time(),
        ])->send();
        dd($response);

        return Reply::successWithData(
            'Deposit successfully',
            [
                'data' => $wallet,
            ]
        );
    }
}
