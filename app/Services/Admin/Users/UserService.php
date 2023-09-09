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
        $inputs['password'] = isset($inputs['password']) ? bcrypt($inputs['password']) : $this->model->password;

        $inputs['avatar'] = !empty($inputs['avatar']) ? Files::upload(
            $inputs['avatar'],
            Files::USER_AVATAR_FOLDER,
            width: 300,
            options: ['isUser' => true]
        ) : $this->model->avatar;

        $inputs['client_id'] = $inputs['client_id'] ?? $this->model->client_id;
        $inputs['type'] = $inputs['type'] ?? $this->model->type;
        $inputs['name'] = $inputs['name'] ?? $this->model->name;
        $inputs['email'] = $inputs['email'] ?? $this->model->email;
        $inputs['mobile'] = $inputs['mobile'] ?? $this->model->mobile;
        $inputs['gender'] = $inputs['gender'] ?? $this->model->gender;
        $inputs['locale'] = $inputs['locale'] ?? $this->model->locale;
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
        if (isset($options['isPrefix']) && $options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $user->save();

        return $user;
    }

    public function getListClientByEmail($email){
        $emails =
            $this->model
                ->where('email', $email)
                ->select('client_id', 'id')
                ->with('client:id,name')->get();
        $listClient = [];
        foreach ($emails as $email){
            $listClient[] = [
                'id' => $email->client_id,
                'name' => $email->client->name
            ];
        }
        return $listClient;
    }
}
