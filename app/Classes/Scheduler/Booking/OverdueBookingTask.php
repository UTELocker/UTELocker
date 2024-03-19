<?php

namespace App\Classes\Scheduler\Booking;

use App\Enums\BookingStatus;
use App\Enums\NotificationType;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\LockerSlotStatus;
use App\Enums\LockerSlotType;
use App\Models\LockerSlot;

class OverdueBookingTask
{
    public function __invoke()
    {
        DB::transaction(function () {
            Booking::whereIn('bookings.status', [BookingStatus::APPROVED])
                    ->where('bookings.end_date', '<=', Carbon::now()->format('Y-m-d H:i'))
                    ->update(['bookings.status' => BookingStatus::EXPIRED]);
        });
    }
}
