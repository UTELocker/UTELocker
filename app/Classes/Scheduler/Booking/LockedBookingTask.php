<?php

namespace App\Classes\Scheduler\Booking;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\HandleNotification;
use App\Enums\LockerSlotStatus;
use App\Enums\LockerSlotType;
use App\Models\LockerSlot;

class LockedBookingTask
{
    use HandleNotification;

    public function __invoke()
    {
        DB::transaction(function () {
            $lockers = LockerSlot::where('status', LockerSlotStatus::AVAILABLE)
                ->where('type', LockerSlotType::CPU)
                ->select('id', 'config', 'locker_id')
                ->get();
            foreach ($lockers as $locker) {
                $configLocker = json_decode($locker->config ?? '{}', true);
                $booking = Booking::where('bookings.status', BookingStatus::EXPIRED)
                    ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                    ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                    ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                    ->where('locker_slots.locker_id', $locker->locker_id)
                    ->where('bookings.end_date', '<=', Carbon::now()->subMinutes(
                        $configLocker['bufferTime'] ?? 30
                    )->format('Y-m-d H:i'))
                    ->select(
                        'bookings.id',
                        'bookings.owner_id',
                        'bookings.client_id',
                        'bookings.locker_slot_id',
                        'lockers.description',
                        'locations.description as address'
                        )
                    ->get();
                $booking->each(function ($item) {
                    Booking::where('bookings.id', $item->id)
                        ->update([
                            'bookings.status' => BookingStatus::LOCKED
                        ]);
                    $this->sendNotification(
                        NotificationType::BOOKING,
                        "Your booking has been locked at locker" . $item->description . " - " . $item->address,
                        $item->owner_id,
                        $item->client_id,
                        'bookings',
                        $item->id,
                    );
                });
            }
        });
    }
}
