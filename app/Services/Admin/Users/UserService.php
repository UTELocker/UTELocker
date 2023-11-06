<?php

namespace App\Services\Admin\Users;

use App\Classes\Common;
use App\Classes\Files;
use App\Enums\UserRole;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Services\BaseService;
use App\Services\Wallets\WalletService;
use Cassandra\Type\UserType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Classes\CommonConstant;
use Illuminate\Support\Facades\Hash;

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

        if (!user()->isSuperUser()) {
            $inputs['client_id'] = user()->client_id;
        } else {
            $inputs['client_id'] = $inputs['client_id'] ?? $this->model->client_id;
        }
        $inputs['type'] = $inputs['type'] ?? $this->model->type;
        $inputs['name'] = $inputs['name'] ?? $this->model->name;
        $inputs['email'] = $inputs['email'] ?? $this->model->email;
        $inputs['mobile'] = $inputs['mobile'] ?? $this->model->mobile;
        $inputs['gender'] = $inputs['gender'] ?? $this->model->gender;
        $inputs['locale'] = $inputs['locale'] ?? $this->model->locale;
        if (isset($inputs['is2FA'])) {
            $inputs['is2FA'] = $inputs['is2FA'] ? CommonConstant::DATABASE_YES : CommonConstant::DATABASE_NO;
        } else {
            $inputs['is2FA'] = CommonConstant::DATABASE_NO;
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
        Common::assignField($this->model, 'is2FA', $inputs);
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

        if (isset($inputs['old_password']) && !Hash::check($inputs['old_password'], $this->model->password)) {
            return false;
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

    public function lookups(User $user)
    {
        return User::userSiteGroup()
            ->where('id', $user->id)
            ->addSelect([
                'users.id',
                'users.name',
                'users.email',
            ])
            ->first();
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getListAdmin()
    {
        return $this->model
            ->where('type', UserRole::ADMIN)
            ->where('client_id', user()->client_id)
            ->select(
                'id',
                'name',
                'email',
            )
            ->get();
    }
}
