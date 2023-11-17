<?php

namespace App\Classes\Scheduler\SystemLocker;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\License;

class CheckLockerLiveTask
{
    public function __invoke()
    {
        $lockerNotUpdate = License::
            leftJoin('lockers', 'lockers.id', '=', 'licenses.locker_id')
            ->leftJoin('locker_system_logs', 'locker_system_logs.license_id', '=', 'licenses.id')
            ->groupBy('licenses.id')
            ->select('licenses.id', 'locker_system_logs.updated_at')
            ->whereNull('locker_system_logs.updated_at')
            ->orWhere('locker_system_logs.updated_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 5 MINUTE)'))
            ->get();
    }
}
