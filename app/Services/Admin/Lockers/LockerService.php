<?php

namespace App\Services\Admin\Lockers;

use App\Classes\Common;
use App\Classes\Files;
use App\Models\Locker;
use App\Services\Admin\Licenses\LicenseService;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LockerService extends BaseService
{
    private LicenseService $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        parent::__construct(new Locker());
        $this->licenseService = $licenseService;
    }

    public function initDefaultData(): static
    {
        $this->model->code = $this->model::generateNextCode();
        $this->model->date_of_manufacture = now(globalSettings()->timezone)->format(globalSettings()->date_format);
        return $this;
    }

    public function add(array $inputs, array $options = [])
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        DB::transaction(function () {
            $this->model->save();
            $this->licenseService->add(['locker_id' => $this->model->id]);
        });
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        if (!empty($inputs['image'])) {
            $inputs['image'] = Files::upload($inputs['image'], 'client-locker', width: 300);
        }
        if (!empty($inputs['date_of_manufacture'])) {
            $inputs['date_of_manufacture'] = Carbon::createFromFormat(
                globalSettings()->date_format,
                $inputs['date_of_manufacture']
            )->format('Y-m-d');
        }
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'code', $inputs);
        Common::assignField($this->model, 'date_of_manufacture', $inputs);
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'description', $inputs);
        Common::assignField($this->model, 'image', $inputs);
    }
}
