<?php

namespace App\Http\Controllers\Api\Payments;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Wallets\WalletService;
use Illuminate\Http\Request;

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
}
