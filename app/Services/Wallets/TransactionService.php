<?php

namespace App\Services\Wallets;

use App\Classes\Common;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Services\BaseService;

class TransactionService extends BaseService
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function initDefaultData(): static
    {
        $this->model->status = TransactionStatus::PENDING;
        $this->model->type = TransactionType::DEPOSIT;

        return $this;
    }

    public function add(array $inputs, array $options = []): Transaction
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

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'user_id', $inputs);
        Common::assignField($this->model, 'payment_method_id', $inputs);
        Common::assignField($this->model, 'amount', $inputs);
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'reference', $inputs);
        Common::assignField($this->model, 'reference_transaction_id', $inputs);
        Common::assignField($this->model, 'balance', $inputs);
        Common::assignField($this->model, 'promotion_balance', $inputs);
        Common::assignField($this->model, 'time', $inputs);
        Common::assignField($this->model, 'content', $inputs);
    }

    public function validateUniqueReference(string $reference): bool
    {
        return $this->model
            ->where('reference', $reference)
            ->count() === 0;
    }
}
