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

        $locker = $this->lockerService->getWithLocation($id);
        $listSlotsNotAvailable = $this->lockerSlotService->getSlotsNotAvailable($id, $startDate, $endDate);
        $module = $this->lockerService->getModulesAvailableBooking($locker, $listSlotsNotAvailable);

        return Reply::successWithData(
            'Get locker successfully',
            [
                'data' =>  [
                    'locker' => $locker,
                    'module' => $module,
                ],
            ]
        );
    }

    public function search(SearchLockersRequest $request) {
        $res = $this->lockerService->search($request->all());
        return Reply::successWithData(
            'Get list lockers successfully',
            [
                'data' => $res,
            ]
        );
    }
}
