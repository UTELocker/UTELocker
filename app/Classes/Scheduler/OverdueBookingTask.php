<?php

namespace App\Classes\Scheduler;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OverdueBookingTask
{
    public function __invoke()
    {
        DB::transaction(function () {
            Booking::whereIn('bookings.status', [BookingStatus::APPROVED])
                ->where('bookings.end_date', '<=', Carbon::now()->subMinutes(30)->format('Y-m-d H:i'))
                ->update(['bookings.status' => BookingStatus::EXPIRED]);
        });
    }
}