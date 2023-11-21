<?php

namespace App\Http\Requests\Api\Bookings;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\LockerSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PutExtendTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'extend_time' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $idBooking = $this->route('id');

                    $booking = Booking::where('id', $idBooking)
                        ->where('owner_id', user()->id)
                        ->where(function ($query) {
                            $query->where('status', BookingStatus::APPROVED)
                                ->orWhere('status', BookingStatus::PENDING);
                        })
                        ->first();

                    if (!$booking) {
                        $fail('Booking not found');
                    }

                    if ($value < 30) {
                        $fail('Extend time must be greater than 30 minutes');
                    }

                    $extendTime = Carbon::parse($booking->end_date)->addMinutes($value);

                    $allBookingOfSlot = LockerSlot::where('locker_slots.id', $booking->locker_slot_id)
                        ->leftJoin('bookings', 'bookings.locker_slot_id', '=', 'locker_slots.id')
                        ->whereBetween('bookings.start_date', [$booking->start_date, $extendTime])
                        ->whereIn('bookings.status', [BookingStatus::APPROVED, BookingStatus::PENDING])
                        ->select('locker_slots.id',  'bookings.start_date', 'bookings.end_date')
                        ->where('bookings.id', '<>', $booking->id)
                        ->get()->toArray();

                    if (count($allBookingOfSlot) > 0) {
                        $fail('Can not extend time');
                    }
                }
            ]
        ];
    }
}
