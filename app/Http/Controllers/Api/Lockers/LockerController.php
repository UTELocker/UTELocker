<?php

namespace App\Http\Controllers\Api\Lockers;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Lockers\GetModulesRequest;
use App\Http\Requests\Api\Lockers\SearchLockersRequest;
use App\Services\Admin\Lockers\LockerService;
use App\Services\Admin\Lockers\LockerSlotService;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    public ?LockerService $lockerService;
    public ?LockerSlotService $lockerSlotService;

    public function __construct(
        LockerService $lockerService,
        LockerSlotService $lockerSlotService
    ) {
        $this->lockerService = $lockerService;
        $this->lockerSlotService = $lockerSlotService;
    }

    public function get() {
        $res = $this->lockerService->all();
        return Reply::successWithData(
            'Get list locker successfully',
            [
                'data' => $res,
            ]
        );
    }

    public function getModules($id, GetModulesRequest $request) {
        $endDate = $request->end_date;
        $startDate = $request->start_date;
        $numberOfSlots = $request->number_of_slots;

        $locker = $this->lockerService->getWithLocation($id);
        if (!$locker) {
            return Reply::error('Locker not found', 'locker_not_found');
        }
        $configLocker = json_decode($this->lockerService->getConfigLocker($locker), true);
        if (!$this->lockerService->isNotExceedingLimitTime($configLocker, $startDate, $endDate)) {
            return Reply::error('Exceeding limit time', 'exceeding_limit_time');
        }
        $listSlotsNotAvailable = $this->lockerSlotService->getSlotsNotAvailable($id, $startDate, $endDate);
        $module = $this->lockerService->getModulesAvailableBooking($locker, $listSlotsNotAvailable);
        $slotsUserBooked = $this->lockerService->getSlotsUserBooked($locker, $id);

        return Reply::successWithData(
            'Get locker successfully',
            [
                'data' =>  [
                    'locker' => $locker,
                    'module' => $module,
                    'configNumberSlot' => [
                        'max' => $configLocker['maxBookings'] ?? 5,
                        'demand' => $numberOfSlots,
                        'used' => $slotsUserBooked[0]->locker_slots_count,
                    ]
                ],
            ]
        );
    }

    public function search(SearchLockersRequest $request) {
        $res = $this->lockerService->search($request->all());
        return Reply::successWithData(
            'Get list lockers successfully',
            [
                'data' => $this->lockerService->filterLimitTimeLocker($res, $request->start_date, $request->end_date),
            ]
        );
    }

    public function getLockerActivities() {
        $res = $this->lockerService->getLockerActivities();
        return Reply::successWithData(
            'Get locker activities successfully',
            [
                'data' => $res,
            ]
        );
    }

    public function getSlots($id) {
        $res = $this->lockerSlotService->getSlots($id);
        return Reply::successWithData(
            'Get locker slots successfully',
            [
                'data' => $this->lockerSlotService->formatOutPutSlots($res),
            ]
        );
    }
}
