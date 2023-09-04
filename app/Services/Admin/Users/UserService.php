<?php

namespace App\Services\Admin\Users;

use App\Classes\Common;
use App\Classes\Files;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Services\BaseService;
use App\Services\Wallets\WalletService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    public const FORM_PREFIX = 'user_';
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct(new User());
        $this->walletService = $walletService;
    }

    /**
     * @throws ApiException
     */
    public function add(array $inputs, array $options = []): Model
    {
        $this->new();
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        DB::transaction(function () {
            $this->model->save();
            $this->walletService->add([
                'user_id' => $this->model->id,
                'balance' => 0,
                'promotion_balance' => 0,
            ]);
        });

        return $this->model;
    }

    /**
     * @throws ApiException
     */
    protected function formatInputData(&$inputs): void
    {
        $inputs['password'] = bcrypt($inputs['password']);
        if (!empty($inputs['avatar'])) {
            $inputs['avatar'] = Files::upload(
                $inputs['avatar'],
                Files::USER_AVATAR_FOLDER,
                width: 300,
                options: ['isUser' => true]
            );
        }
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'name', $inputs);
        Common::assignField($this->model, 'email', $inputs);
        Common::assignField($this->model, 'password', $inputs);
        Common::assignField($this->model, 'client_id', $inputs);
        Common::assignField($this->model, 'avatar', $inputs);
        Common::assignField($this->model, 'mobile', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'gender', $inputs);
        Common::assignField($this->model, 'locale', $inputs);
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update(User $user, array $inputs, array $options = []): user
    {
        $this->setModel($user);
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $user->save();

        return $user;
    }
}
