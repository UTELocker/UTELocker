<?php

namespace App\Classes\Scheduler;

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

class OverdueWarningBookingTask
{
    use HandleNotification;
    public function __invoke()
    {
        $lockers = LockerSlot::where('status', LockerSlotStatus::AVAILABLE)
            ->where('type', LockerSlotType::CPU)
            ->select('id', 'config', 'locker_id')
            ->get();
        foreach ($lockers as $locker) {
            $configLocker = json_decode($locker->config ?? '{}', true);
            $listBookings = Booking::whereIn('bookings.status', [BookingStatus::APPROVED])
                ->whereBetween('bookings.end_date', [
                    Carbon::now()->format('Y-m-d H:i'),
                    Carbon::now()->addMinutes(
                        $configLocker['bufferTime'] ?? 30
                    )->format('Y-m-d H:i'),
                ])
                ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
                ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
                ->where('locker_slots.locker_id', $locker->locker_id)
                ->select(
                    'bookings.id',
                    'bookings.owner_id',
                    'bookings.client_id',
                    'bookings.end_date',
                    'locations.description as address',
                    'lockers.code as locker_code')
                ->get();
            Log::info([
                Carbon::now()->addMinutes(
                    $configLocker['bufferTime'] ?? 30
                )->format('Y-m-d H:i'),
                Carbon::now()->format('Y-m-d H:i')
            ]);
            foreach ($listBookings as $booking) {
                $diffTime = Carbon::now()->diffInMinutes($booking->end_date);

                switch ($diffTime) {
                    case 15:
                        $this->sendNotification(
                            NotificationType::BOOKING,
                            "Time expend will end in 20 minutes at locker $booking->locker_code - $booking->address",
                            $booking->owner_id,
                            $booking->client_id,
                            'bookings',
                            $booking->id,
                        );
                        break;
                    case 5:
                        $this->sendNotification(
                            NotificationType::BOOKING,
                            "Time expend will end in 15 minutes at locker $booking->locker_code - $booking->address",
                            $booking->owner_id,
                            $booking->client_id,
                            'bookings',
                            $booking->id,
                        );
                        break;
                    default:
                        break;
                }
            }

        }
    }
}
