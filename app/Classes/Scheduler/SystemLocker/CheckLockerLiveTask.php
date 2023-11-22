<?php

namespace App\Classes\Scheduler\SystemLocker;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\License;
use App\Enums\LockerStatus;
use App\Traits\HandleNotification;
use App\Enums\NotificationType;
use App\Models\Locker;
use Illuminate\Support\Facades\Log;

class CheckLockerLiveTask
{
    use HandleNotification;

    public function __invoke()
    {
        $lockers = License::
            leftJoin('lockers', 'lockers.id', '=', 'licenses.locker_id')
            ->leftJoin('locker_system_logs', 'locker_system_logs.license_id', '=', 'licenses.id')
            ->select('licenses.id',
                'locker_system_logs.updated_at',
                'lockers.code',
                'lockers.id as locker_id',
                'licenses.client_id',
                )
            ->groupBy('licenses.id')
            ->where('lockers.status', LockerStatus::IN_USE)
            ->whereNotNull('licenses.client_id')
            ->where(function ($query) {
                $query->whereNull('locker_system_logs.updated_at')
                    ->orWhere('locker_system_logs.updated_at', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 10 MINUTE)'));
            })
            ->get();
        Log::info('CheckLockerLiveTask: ' . $lockers->count() . ' lockers');
        $lockers->each(function ($locker) {
            Log::info([
                'locker' => $locker
            ]);
            if (!$locker->updated_at) {
                Locker::where('id', $locker->locker_id)->update([
                    'status' => LockerStatus::PENDING_BROKEN,
                ]);
                $admins = License::getAdmins($locker->id);
                Log::info([
                    'admins' => $admins
                ]);
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
