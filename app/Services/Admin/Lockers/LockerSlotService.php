<?php

namespace App\Services\Admin\Lockers;

use App\Classes\Common;
use App\Models\LockerSlot;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LockerSlotService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new LockerSlot());
    }

    public function add(array $inputs, array $options = [])
    {
        $this->new();
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    protected function formatInputData(&$inputs)
    {
        if (!isset($inputs['locker_id'])) {
            $inputs['locker_id'] = $this->model->locker_id;
        }
    }

    protected function setModelFields($inputs)
    {
        Common::assignField($this->model, 'locker_id', $inputs);
        Common::assignField($this->model, 'type', $inputs);
        Common::assignField($this->model, 'row', $inputs);
        Common::assignField($this->model, 'column', $inputs);
        Common::assignField($this->model, 'config', $inputs);
        Common::assignField($this->model, 'status', $inputs);
    }

    public function bulkUpdate($lockerId, array $oldModules, array $form)
    {
        DB::transaction(function () use ($lockerId, $oldModules, $form) {
            $this->deleteRemovedSlots($oldModules, $form['modules']);
            $this->updateSlots($form['modules'], $lockerId);
        });
    }

    private function deleteRemovedSlots(array $oldModules, array $newModules)
    {
        $deletedModules = [];

        foreach ($oldModules as $oldRow) {
            foreach ($oldRow as $oldColumn) {
                $oldId = Arr::get($oldColumn, 'id');

                if ($oldId == -1) {
                    continue;
                }

                $found = $this->isNewIdFound($oldId, $newModules);

                if (!$found) {
                    $deletedModules[] = $oldId;
                }
            }
        }

        if (!empty($deletedModules)) {
            $this->model->whereIn('id', $deletedModules)->delete();
        }
    }

    private function isNewIdFound($oldId, array $newModules)
    {
        foreach ($newModules as $newRow) {
            foreach ($newRow as $newColumn) {
                $newId = Arr::get($newColumn, 'id');

                if ($oldId == $newId) {
                    return true;
                }
            }
        }

        return false;
    }

    private function updateSlots(array $modules, $lockerId)
    {
        foreach ($modules as $row => $columns) {
            foreach ($columns as $column => $module) {
                $id = Arr::get($module, 'id');
                $type = Arr::get($module, 'type');
                $config = Arr::get($module, 'config');
                $status = Arr::get($module, 'status');

                if ($id == -1) {
                    $this->add([
                        'locker_id' => $lockerId,
                        'type' => $type,
                        'row' => $row,
                        'column' => $column,
                        'config' => $config,
                        'status' => $status,
                    ]);
                } else {
                    $this->update($id, [
                        'type' => $type,
                        'config' => $config,
                        'status' => $status,
                        'row' => $row,
                        'column' => $column,
                    ]);
                }
            }
        }
    }

    private function update($lockerId, array $inputs)
    {
        $this->model = $this->get($lockerId);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }
}