<?php

namespace App\Services\Admin\Users;

use App\Classes\Common;
use App\Classes\Files;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class UserService extends BaseService
{
    public const FORM_PREFIX = 'user_';

    public function __construct()
    {
        parent::__construct(new User());
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

        $this->model->save();

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
                'user-avatar',
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
}
