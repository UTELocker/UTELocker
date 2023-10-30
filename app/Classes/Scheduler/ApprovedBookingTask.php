<?php

namespace App\Classes\Scheduler;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovedBookingTask
{
    public function __invoke()
    {
        DB::transaction(function () {
            Booking::whereIn('bookings.status', [BookingStatus::PENDING])
                ->where('bookings.start_date', '<=', Carbon::now()->format('Y-m-d H:i'))
                ->update(['bookings.status' => BookingStatus::APPROVED]);
        });
    }
}
