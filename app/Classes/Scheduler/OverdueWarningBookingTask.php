<?php

namespace App\Classes\Scheduler;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\HandleNotification;

class OverdueWarningBookingTask
{
    use HandleNotification;
    public function __invoke()
    {
        $listBookings = Booking::whereIn('bookings.status', [BookingStatus::APPROVED])
            ->whereBetween('bookings.end_date', [
                Carbon::now()->subMinutes(30)->format('Y-m-d H:i'),
                Carbon::now()->format('Y-m-d H:i')
            ])
            ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
            ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
            ->select(
                'bookings.id',
                'bookings.owner_id',
                'bookings.client_id',
                'bookings.end_date',
                'locations.description as address',
                'lockers.code as locker_code')
            ->get();

        foreach ($listBookings as $booking) {
            $diffTime = Carbon::now()->diffInMinutes($booking->end_date);

            switch ($diffTime) {
                case 20:
                    $this->sendNotification(
                        NotificationType::BOOKING,
                        "Time expend will end in 20 minutes at locker $booking->locker_code - $booking->address",
                        $booking->owner_id,
                        $booking->client_id,
                        'bookings',
                        $booking->id,
                    );
                    break;
                case 15:
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
