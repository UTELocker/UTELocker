<?php

namespace App\Services\Admin\Clients;

use App\Classes\Common;
use App\Classes\Files;
use App\Exceptions\ApiException;
use App\Models\Client;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class ClientService extends BaseService
{
    public const FORM_PREFIX = 'client_';

    public function __construct()
    {
        parent::__construct(new Client());
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
        if (!empty($inputs['logo'])) {
            $inputs['logo'] = Files::upload($inputs['logo'], 'client-logo', width: 300);
        }
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'name', $inputs);
        Common::assignField($this->model, 'app_name', $inputs);
        Common::assignField($this->model, 'email', $inputs);
        Common::assignField($this->model, 'phone', $inputs);
        Common::assignField($this->model, 'logo', $inputs);
        Common::assignField($this->model, 'address', $inputs);
        Common::assignField($this->model, 'website', $inputs);
    }

    public function get($id)
    {
        return $this->model->canAccess()->findOrFail($id);
    }

    /**
     * @throws ApiException
     */
    public function update(Client $client, array $inputs, array $options = []): Client
    {
        $this->setModel($client);
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $client->save();

        return $client;
    }
}
