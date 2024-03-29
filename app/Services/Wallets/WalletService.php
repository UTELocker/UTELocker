<?php

namespace App\Services\Wallets;

use App\Classes\Common;
use App\Classes\CommonConstant;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $this->model->password = Hash::make(CommonConstant::DEFAULT_PASSWORD);

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
        $inputs['password'] = $this->model->password;
        $inputs['user_id'] = isset($inputs['user_id']) ? $inputs['user_id'] : user()->id;
        $inputs['balance'] = $this->model->balance;
        $inputs['promotion_balance'] = $this->model->promotion_balance;
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'user_id', $inputs);
        Common::assignField($this->model, 'balance', $inputs);
        Common::assignField($this->model, 'promotion_balance', $inputs);
        Common::assignField($this->model, 'password', $inputs);
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

            $content = __('messages.depositSuccess', [
                'amount' => $amount
            ]);

            $transaction = $this->transactionService->add([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethodId,
                'amount' => $amount,
                'reference' => $reference,
                'reference_transaction_id' => $getTransactionId,
                'status' => TransactionStatus::SUCCESS,
                'type' => TransactionType::DEPOSIT,
                'content' => $content,
                'balance' => $wallet->balance,
                'promotion_balance' => $wallet->promotion_balance,
                'time' => now()
            ]);

            $this->sendNotification(
                NotificationType::PAYMENT,
                $content,
                $user->id,
                $user->client_id,
                NotificationParentTable::TABLE_TRANSACTIONS,
                $transaction->id
            );
        });

        return $transaction;
    }

    public function getTransactionsByUserId(User $user)
    {
        return Transaction::where('user_id', $user->id)
            ->with('paymentMethod')
            ->get();
    }

    public function auth($auth)
    {
        $password = $auth['password'];

        if (!Auth::guard('wallets')->attempt(['user_id' => user()->id, 'password' => $password])) {
            return false;
        }

        return true;
    }

    public function update(Wallet $wallet, $inputs)
    {
        $this->setModel($wallet);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        if (isset($inputs['old_password_is2FA'])) {
            if (Hash::check($inputs['old_password_is2FA'], $this->model->password)) {
                $this->model->password = bcrypt($inputs['password_is2FA']);
            } else {
                return false;
            }
        }

        $this->model->save();

        return $this->model;
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }
}
