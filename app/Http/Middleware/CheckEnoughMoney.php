<?php

namespace App\Http\Middleware;

use App\Classes\Reply;
use App\Models\LockerSlot;
use App\Models\Wallet;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckEnoughMoney
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $wallet = Wallet::find(user()->wallet_id);
        if (isset($request['list_slots_id'])) {
            $amount = LockerSlot::calculatePriceBooking(
                $request['list_slots_id'],
                $request['start_date'], $request['end_date']
            );
        } else {
            $bookingId = $request->route('id');
            $booking = LockerSlot::leftJoin('bookings', 'locker_slots.id', '=', 'bookings.locker_slot_id')
                ->where('bookings.id', $bookingId)
                ->select('locker_slots.locker_id', 'bookings.start_date', 'bookings.end_date')
                ->first();
            $amount = LockerSlot::calculatePriceBooking(
                [$booking->locker_id],
                $booking->end_date,
                Carbon::parse($booking->end_date)->addMinutes($request['extend_time'])->format('Y-m-d H:i:s')
            );
        }

        if ($wallet->balance + $wallet->promotion_balance < $amount) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Not enough money',
            ]);
        }
        return $next($request);
    }
}
