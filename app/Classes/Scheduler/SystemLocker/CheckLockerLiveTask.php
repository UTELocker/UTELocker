<?php

namespace App\Classes\Scheduler\SystemLocker;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\License;
use App\Enums\LockerStatus;
use App\Traits\HandleNotification;
use App\Enums\NotificationType;

class CheckLockerLiveTask
{
    use HandleNotification;

    public function __invoke()
    {
        $lockers = License::
            leftJoin('lockers', 'lockers.id', '=', 'licenses.locker_id')
            ->leftJoin('locker_system_logs', 'locker_system_logs.license_id', '=', 'licenses.id')
            ->where(function ($query) {
                $query->where('licenses.status', LockerStatus::IN_USE)
                    ->orWhere('licenses.status', LockerStatus::AVAILABLE);
            })
            ->groupBy('licenses.id')
            ->select('licenses.id', 'locker_system_logs.updated_at'. 'lockers.code')
            ->whereNull('locker_system_logs.updated_at')
            ->orWhere('locker_system_logs.updated_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 5 MINUTE)'))
            ->get();
        $lockers->each(function ($locker) {
            if (!$locker->updated_at) {
                $locker->update([
                    'status' => LockerStatus::PENDING_BROKEN,
                ]);
                $admins = License::getAdmins($locker->id);
                foreach ($admins as $admin) {
                    $this->sendNotification(
                        NotificationType::LOCKER_BROKEN,
                        'Locker ' . $locker->code . ' is broken',
                        $admin->id,
                        $locker->client_id,
                        'lockers',
                        $locker->id,
                    );
                }
            }
        });
    }
}
