<?php

namespace App\Http\Middleware;

use App\Classes\Reply;
use App\Models\LockerSlot;
use App\Models\Wallet;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $amount = LockerSlot::caculatePriceBooking($request['list_slots_id'], $request['start_date'], $request['end_date']);

        if ($wallet->balance + $wallet->promotion_balance < $amount) {
            return Reply::error('Not enough money');
        }
        return $next($request);
    }
}
