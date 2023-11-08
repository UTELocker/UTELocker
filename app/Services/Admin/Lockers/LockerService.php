<?php

namespace App\Services\Admin\Lockers;

use App\Classes\Common;
use App\Classes\Files;
use App\Enums\BookingStatus;
use App\Enums\LockerSlotStatus;
use App\Enums\LockerSlotType;
use App\Enums\LockerStatus;
use App\Models\Locker;
use App\Services\Admin\Licenses\LicenseService;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LockerService extends BaseService
{
    private LicenseService $licenseService;
    private LockerSlotService $lockerSlotService;
    private array $slotDefault = [
        LockerSlotType::SLOT,
        LockerSlotType::CPU,
        LockerSlotType::SLOT
    ];

    public function __construct(LicenseService $licenseService, LockerSlotService $lockerSlotService)
    {
        parent::__construct(new Locker());
        $this->licenseService = $licenseService;
        $this->lockerSlotService = $lockerSlotService;
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
        DB::transaction(function () use ($inputs) {
            $this->model->save();
            $this->licenseService->add([
                'locker_id' => $this->model->id,
                'warranty_duration' => $inputs['warranty_duration'],
            ]);
            $this->addDefaultSlots();
        });
        return $this->model;
    }

    public function update(Locker $locker, array $inputs, array $options = []): locker
    {
        $this->setModel($locker);
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $locker->save();

        return $locker;
    }

    protected function formatInputData(&$inputs)
    {
        if (isset($inputs['image'])) {
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
        Common::assignField($this->model, 'location_id', $inputs);
    }

    private function addDefaultSlots()
    {
        foreach ($this->slotDefault as $key => $type) {
            $this->lockerSlotService->add([
                'locker_id' => $this->model->id,
                'type' => $type,
                'row' => 1,
                'column' => $key + 1,
                'status' => LockerSlotStatus::AVAILABLE
            ]);
        }
    }

    public function getModules($slotList = null, Locker $locker = null): array
    {
        $modules = [];
        $slots = $slotList ?: $locker->lockerSlots;
        foreach ($slots as $slot) {
            $modules[$slot->row][$slot->column] = $slot->toArray();
        }
        return $modules;
    }

    public function getModulesAvailableBooking(Locker $locker, $slotsNotAvailable)
    {
        $modules = [];
        $slots = $locker->lockerSlots;

        foreach ($slots as $slot) {
            $modules[$slot->row][$slot->column] = $slot->toArray();
            if ($slot->type === LockerSlotType::SLOT) {
                $modules[$slot->row][$slot->column]['statusSlot'] =
                    in_array($slot->id, $slotsNotAvailable) ?
                        LockerSlotStatus::BOOKED :
                        LockerSlotStatus::AVAILABLE;
            } else {
                $modules[$slot->row][$slot->column]['statusSlot'] = LockerSlotStatus::LOCKED;
            }
        }

        ksort($modules , SORT_NUMERIC);
        foreach ($modules as &$module) {
            ksort($module , SORT_NUMERIC);
        }

        return $modules;
    }

    public function get($id) {
        return $this->model->findOrfail($id);
    }

    public function search($inputs) {
        $startDate = Carbon::createFromFormat('Y-m-d H:i', $inputs['start_date'])->subMinutes(30);
        $endDate = Carbon::createFromFormat('Y-m-d H:i', $inputs['end_date'])->addMinutes(30);
        $numberSlot = $inputs['number_of_slots'] ?? 1;
        $locations = $inputs['location_ids'] ?? null;

        return $this->model
            ->select(
                'lockers.id', 'lockers.code', 'lockers.image', 'lockers.description',
                'lockers.status', 'locations.description as address', 'locker_slots.config'
            )
            ->join('locations', 'locations.id', '=', 'lockers.location_id')
            ->join('licenses', 'licenses.locker_id', '=', 'lockers.id')
            ->leftJoin('locker_slots', 'locker_slots.locker_id', '=', 'lockers.id')
            ->where('locker_slots.type', LockerSlotType::CPU)
            ->where('licenses.client_id', user()->client_id)
            ->when($locations, function ($query, $locations) {
                $query->whereIn('locations.id', $locations);
            })
            ->where('lockers.status', '==', LockerStatus::IN_USE)
            ->withCount(['lockerSlots' => function ($query) use($startDate, $endDate) {
                $query->where('type', LockerSlotType::SLOT)
                    ->whereNotIn('id', function ($q) use ($startDate, $endDate) {
                        $q->select('locker_slot_id')
                        ->from('bookings')
                        ->where(function ($q) {
                            $q->where('status', BookingStatus::PENDING)
                                ->orWhere('status', BookingStatus::APPROVED);
                        })
                        ->where(function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate]);
                        });
                    });
            }])
            ->having('locker_slots_count', '>=', $numberSlot)
            ->get();
    }

    public function getWithLocation($id) {
        return $this->model
            ->select(
                'lockers.id', 'lockers.code', 'lockers.image', 'lockers.description',
                'lockers.status', 'locations.description as address',
                'locations.latitude', 'locations.longitude'
            )
            ->join('licenses', 'licenses.locker_id', '=', 'lockers.id')
            ->join('locations', 'locations.id', '=', 'lockers.location_id')
            ->where('lockers.id', $id)
            ->where('licenses.client_id', user()->client_id)
            ->first();
    }

    public function getSlotsUserBooked(Locker $locker, $lockerId)
    {
        return $locker
            ->select('lockers.id')
            ->where('lockers.id', $lockerId)
            ->withCount(['lockerSlots' => function ($query) {
                $query->where('type', LockerSlotType::SLOT)
                    ->leftJoin('bookings', 'bookings.locker_slot_id', '=', 'locker_slots.id')
                    ->where('bookings.owner_id', user()->id)
                    ->where(function ($q) {
                        $q->where('bookings.status', BookingStatus::PENDING)
                            ->orWhere('bookings.status', BookingStatus::APPROVED);
                    });
            }])
            ->get();
    }

    public function getConfigLocker(Locker $locker)
    {
        return $locker
            ->lockerCPUType()
            ->select('locker_slots.config')
            ->first()->config;
    }

    public function filterLimitTimeLocker($data, $startDate, $endDate)
    {
        return $data->filter(function ($item) use ($startDate, $endDate) {
            $config = json_decode($item->config, true);
            return $this->isNotExceedingLimitTime($config, $startDate, $endDate);
        });
    }

    public function isNotExceedingLimitTime($configLocker, $startDate, $endDate): bool
    {
        if (empty($configLocker)) {
            return true;
        }
        $limitMinutes = $configLocker['days'] * 24 * 60 + $configLocker['hours'] * 60 + $configLocker['minutes'];
        $diffMinutes = Carbon::createFromFormat('Y-m-d H:i', $endDate)->diffInMinutes(
                Carbon::createFromFormat('Y-m-d H:i', $startDate)
            );
        return $diffMinutes <= $limitMinutes || $limitMinutes === 0;
    }

    public function getLockerActivities()
    {
        return $this->model
            ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
            ->where(function ($q) {
                $q->where('lockers.status', LockerStatus::IN_USE)
                    ->orWhere('lockers.status', LockerStatus::AVAILABLE);
            })
            ->where('locations.client_id', user()->client_id)
            ->select(
                'lockers.id', 'lockers.code', 'locations.description as address',
            )
            ->get();
    }
}
