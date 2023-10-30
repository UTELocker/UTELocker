<?php

namespace App\Services\Wallets;

use App\Classes\Common;
use App\Enums\NotificationParentTable;
use App\Enums\NotificationType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\BaseService;
use App\Traits\HandleNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletService extends BaseService
{
    use HandleNotification;
    protected TransactionService $transactionService;

    public function __construct(Wallet $model)
    {
        parent::__construct($model);
        $this->transactionService = new TransactionService(new Transaction());
    }

    public function initDefaultData(): static
    {
        $this->model->balance = 0;
        $this->model->promotion_balance = 0;

        return $this;
    }

    public function add(array $inputs, array $options = []): Model
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();

        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        return;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'user_id', $inputs);
        Common::assignField($this->model, 'balance', $inputs);
        Common::assignField($this->model, 'promotion_balance', $inputs);
    }

    public function getWalletByUserId(User $user): Wallet
    {
        return Wallet::where('user_id', $user->id)->get()->first();
    }

    public function deposit(
        User $user,
        string $reference,
        string $getTransactionId,
        int $amount,
        int $paymentMethodId
    ): Transaction {
        $validateTransaction = $this->transactionService->validateUniqueReference($reference);

        if (!$validateTransaction) {
            throw new \Exception('Transaction reference is duplicated');
        }

        DB::transaction(function () use (
            $user, $reference, $getTransactionId, $amount, $paymentMethodId, &$transaction
        ) {
            $wallet = $this->getWalletByUserId($user);
            $wallet->balance += $amount;
            $wallet->save();

            $transaction = $this->transactionService->add([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethodId,
                'amount' => $amount,
                'reference' => $reference,
                'reference_transaction_id' => $getTransactionId,
                'status' => TransactionStatus::SUCCESS,
                'type' => TransactionType::DEPOSIT
            ]);

            $content = __('messages.depositSuccess', [
                'amount' => $amount
            ]);

            $this->sendNotification(
                NotificationType::PAYMENT,
                $content,
                $user->id,
                $user->client_id,
                NotificationParentTable::TABLE_BOOKINGS,
                $transaction->id
            );
        });

        return $transaction;
    }
}
