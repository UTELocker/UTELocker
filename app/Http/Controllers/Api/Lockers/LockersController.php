<?php

namespace App\Http\Controllers\Api\Lockers;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Admin\Lockers\LockerService;
use Illuminate\Http\Request;

class LockersController extends Controller
{
    public ?LockerService $lockerService;

    public function __construct(LockerService $lockerService)
    {
        $this->lockerService = $lockerService;
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

    public function getModules($id) {
        $locker = $this->lockerService->get($id);
        $module = $this->lockerService->getModules($locker);

        return Reply::successWithData(
            'Get locker successfully',
            [
                'data' =>  ksort($module),
            ]
        );
    }
}
