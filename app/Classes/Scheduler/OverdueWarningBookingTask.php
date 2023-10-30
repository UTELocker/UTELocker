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
            ->get();

        foreach ($listBookings as $booking) {
            $diffTime = Carbon::now()->diffInMinutes($booking->end_date);

            switch ($diffTime) {
                case 20:
                    $this->sendNotification(
                        NotificationType::BOOKING,
                        'Your booking ended 20 minutes ago',
                        $booking->owner_id,
                        $booking->client_id,
                        'bookings',
                        $booking->id,
                    );
                    break;
                case 15:
                    $this->sendNotification(
                        NotificationType::BOOKING,
                        'Your booking ended 15 minutes ago',
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
