<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\Wallets\TransactionService;
use Illuminate\Support\Facades\Log;
use App\Enums\BookingStatus;

class BookingObserver
{
    public ?TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        $percentage = siteGroup()->refund_soon_cancel_booking;
        if ($booking->status == BookingStatus::CANCELLED && $percentage > 0) {
            $this->transactionService->refund($booking->transaction_id, $percentage);
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
