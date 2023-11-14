<?php

namespace App\Services\Admin\Lockers;

use App\Classes\Common;
use App\Enums\BookingStatus;
use App\Enums\LockerSlotType;
use App\Models\LockerSlot;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Locker;

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

    private function update($slotId, array $inputs)
    {
        $this->model = $this->get($slotId);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();
        return $this->model;
    }

    public function updateSetting($slotId, $status, $config)
    {
        $config = json_encode($config);
        $this->model->where('id', $slotId)->update(['status' => $status, 'config' => $config]);
    }

    public function getSlotsNotAvailable(Locker $locker, mixed $startDate, mixed $endDate)
    {
        $slotCPU = $this->model
            ->where('locker_id', $locker->id)
            ->where('type', LockerSlotType::CPU)
            ->select('config')
            ->first();
        $configLocker = json_decode($slotCPU->config, true);
        $startDate = Carbon::parse($startDate)->subMinutes($configLocker['bufferTime'])->toDateTimeString();
        $endDate = Carbon::parse($endDate)->addMinutes($configLocker['bufferTime'])->toDateTimeString();

        return $this->model->where('locker_id', $locker->id)
            ->leftJoin('bookings', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->where('locker_slots.type', '=', LockerSlotType::SLOT)
            ->where(function ($q) {
                $q->where('bookings.status', BookingStatus::PENDING)
                    ->orWhere('bookings.status', BookingStatus::APPROVED);
            })
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.start_date', [$startDate, $endDate])
                    ->orWhereBetween('bookings.end_date', [$startDate, $endDate]);
            })
            ->select('locker_slots.id')
            ->get()->pluck('id')->toArray();
    }

    public function get($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getSlots($lockerId)
    {
        return $this->model->where('locker_id', $lockerId)
            ->where(function($q) {
                $q->where('type', LockerSlotType::SLOT)
                    ->orWhere('type', LockerSlotType::CPU);
            })
            ->select('id', 'type', 'row', 'column', 'config')
            ->orderBy('row')
            ->orderBy('column')
            ->get();
    }

    public function formatOutPutSlots($slots)
    {
        $modules = [];
        $count = 1;

        $configLocker = json_decode($slots->where('type', LockerSlotType::CPU)->first()->config, true);
        $prefix = $configLocker['prefix'] ?? '';

        foreach ($slots as $slot) {
            if ($slot->type == LockerSlotType::CPU) {
                continue;
            }
            $modules[] = [
                'id' => $slot->id,
                'type' => $slot->type,
                'config' => json_decode($slot->config),
                'status' => $slot->status,
                'name' => $prefix . $count++,
            ];
        }

        return $modules;
    }

    public function getByLockerId($lockerId)
    {
        return $this->model->where('locker_id', $lockerId)->get();
    }
}
