<?php

namespace App\Http\Controllers\Api\Payments;

use App\Classes\Reply;
use App\DataTables\TransactionsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payments\DepositRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Libs\PaymentGateway\PaymentGateway;
use App\Libs\PaymentMethodConfig\PaymentMethodLoader;
use App\Services\Admin\Payments\PaymentMethodService;
use App\Services\Wallets\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Services\Wallets\TransactionService;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;

class PaymentController extends Controller
{
    private WalletService $walletService;
    private TransactionService $transactionService;
    private PaymentMethodService $paymentMethodService;

    public function __construct(
        WalletService $walletService,
        PaymentMethodService $paymentMethodService,
        TransactionService $transactionService
    ) {
        parent::__construct();
        $this->walletService = $walletService;
        $this->paymentMethodService = $paymentMethodService;
        $this->transactionService = $transactionService;
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
            $paymentMethodConfig->getAmountFieldName() => Arr::get($request->all(), 'amount')
        ])
            ->setReturnUrl(route('portal.wallet.deposit.callback', ['methodId' => $paymentMethod->id]))
            ->send();

        return Reply::successWithData(
            'Get URL successfully',
            [
                'redirectUrl' => $response->getRedirectUrl(),
            ]
        );
    }

    public function depositCallback(Request $request, $methodId)
    {
        $paymentMethod = $this->paymentMethodService->get($methodId);
        $paymentMethodConfig = PaymentMethodLoader::load($paymentMethod->type, $paymentMethod->config);
        $gateway = PaymentGateway::make($paymentMethodConfig->getGateway());

        $response = $gateway->completePurchase()->send();

        if ($response->isSuccessful()) {
            $transaction = $this->walletService->deposit(
                $request->user(),
                $response->getTransactionId(),
                $response->getTransactionReference(),
                $response->getAmount(),
                $paymentMethod->id
            );

            return redirect()->route('wallet.transactions', [
                'transactionId' => $transaction->id,
                'type' => 'deposit',
            ]);
        } else {
            if (empty( $response->getTransactionReference())) {
                return redirect()->route('wallet');
            }
            $transaction = $this->transactionService->add([
                'user_id' => $request->user()->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $response->getAmount(),
                'reference' => $response->getTransactionReference(),
                'reference_transaction_id' => $response->getTransactionId(),
                'status' => TransactionStatus::FAILED,
                'type' => TransactionType::DEPOSIT,
                'content' => __('messages.depositFailed') . ' at ' . $paymentMethod->name . ' - ' . $response->getMessage(),
                'balance' => $request->user()->wallet->balance,
                'promotion_balance' => $request->user()->wallet->promotion_balance,
                'time' => now()
            ]);
            return redirect()->route('wallet.transactions', [
                'transactionId' => $transaction->id,
                'type' => 'deposit',
            ]);
        }
    }

    public function getTransactions(TransactionsDataTable $dataTable, Request $request)
    {
        $user = $request->user();
        $transactions = $this->walletService->getTransactionsByUserId($user);
        return Reply::successWithData(
            'Get transactions successfully',
            [
                'data' => $transactions,
            ]
        );
    }

    public function auth(Request $request)
    {
        $data = $request->all();
        $result = $this->walletService->auth($data);

        if ($result) {
            return Reply::success('Auth successfully');
        } else {
            return Reply::error('Auth failed');
        }
    }
}
