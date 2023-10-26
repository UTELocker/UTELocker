<?php

namespace App\Services\Wallets;

use App\Classes\Common;
use App\Models\User;
use App\Models\Wallet;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class WalletService extends BaseService
{
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
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
        return $this->model
            ->where('user_id', $user->id)
            ->select([
                'balance',
                'promotion_balance',
            ])
            ->first();
    }
}
