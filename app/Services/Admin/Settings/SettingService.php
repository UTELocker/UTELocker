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
        $inputs['status'] = $inputs['status'] ?? $this->model->status;
        $inputs['allow_signup'] = $inputs['allow_signup'] ?? $this->model->allow_signup;
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'locale', $inputs);
        Common::assignField($this->model, 'date_format', $inputs);
        Common::assignField($this->model, 'time_format', $inputs);
        Common::assignField($this->model, 'timezone', $inputs);  Common::assignField($this->model, 'pusher_app_id', $inputs);
        Common::assignField($this->model, 'pusher_app_key', $inputs);
        Common::assignField($this->model, 'pusher_app_secret', $inputs);
        Common::assignField($this->model, 'pusher_app_cluster', $inputs);
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'allow_signup', $inputs);
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
