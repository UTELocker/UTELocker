<?php

namespace App\Services\Admin\Settings;

use App\Classes\Common;
use App\Exceptions\ApiException;
use App\Models\GlobalSetting;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use App\Enums\UserRole;

class SettingService extends BaseService
{
    public const FORM_PREFIX = 'globalSetting_';

    public function __construct()
    {
        parent::__construct(new GlobalSetting());
    }

    /**
     * @throws ApiException
     */
    protected function formatInputData(&$inputs): void
    {
        $inputs['date_format'] = $inputs['date_format'] ?? $this->model->date_format;
        $inputs['locale'] = $inputs['locale'] ?? $this->model->locale;
        $inputs['time_format'] = $inputs['time_format'] ?? $this->model->time_format;
        $inputs['timezone'] = $inputs['timezone'] ?? $this->model->timezone;
        $inputs['pusher_app_id'] = $inputs['pusher_app_id'] ?? $this->model->pusher_app_id;
        $inputs['pusher_app_key'] = $inputs['pusher_app_key'] ?? $this->model->pusher_app_key;
        $inputs['pusher_app_secret'] = $inputs['pusher_app_secret'] ?? $this->model->pusher_app_secret;
        $inputs['pusher_app_cluster'] = $inputs['pusher_app_cluster'] ?? $this->model->pusher_app_cluster;
        $inputs['firebase_api_key'] = $inputs['firebase_api_key'] ?? $this->model->firebase_api_key;
        $inputs['firebase_auth_domain'] = $inputs['firebase_auth_domain'] ?? $this->model->firebase_auth_domain;
        $inputs['firebase_project_id'] = $inputs['firebase_project_id'] ?? $this->model->firebase_project_id;
        $inputs['firebase_storage_bucket'] = $inputs['firebase_storage_bucket'] ?? $this->model->firebase_storage_bucket;
        $inputs['firebase_messaging_sender_id'] = $inputs['firebase_messaging_sender_id'] ?? $this->model->firebase_messaging_sender_id;
        $inputs['firebase_app_id'] = $inputs['firebase_app_id'] ?? $this->model->firebase_app_id;
        $inputs['firebase_measurement_id'] = $inputs['firebase_measurement_id'] ?? $this->model->firebase_measurement_id;
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'locale', $inputs);
        Common::assignField($this->model, 'date_format', $inputs);
        Common::assignField($this->model, 'time_format', $inputs);
        Common::assignField($this->model, 'timezone', $inputs);
        Common::assignField($this->model, 'pusher_app_id', $inputs);
        Common::assignField($this->model, 'pusher_app_key', $inputs);
        Common::assignField($this->model, 'pusher_app_secret', $inputs);
        Common::assignField($this->model, 'pusher_app_cluster', $inputs);
        Common::assignField($this->model, 'firebase_api_key', $inputs);
        Common::assignField($this->model, 'firebase_auth_domain', $inputs);
        Common::assignField($this->model, 'firebase_project_id', $inputs);
        Common::assignField($this->model, 'firebase_storage_bucket', $inputs);
        Common::assignField($this->model, 'firebase_messaging_sender_id', $inputs);
        Common::assignField($this->model, 'firebase_app_id', $inputs);
        Common::assignField($this->model, 'firebase_measurement_id', $inputs);
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update(GlobalSetting $globalSetting, array $inputs, array $options = []): globalSetting
    {
        $this->setModel($globalSetting);
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $globalSetting->save();

        return $globalSetting;
    }
}
