<?php

namespace App\Http\Requests\Api\Booking;

use App\Enums\BookingStatus;
use App\Models\LockerSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
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
                'string',
                function ($attribute, $value, $fail) {
                    $idBooking = $this->route('id');

                    $booking = Booking::where('id', $idBooking)
                        ->where('owner_id', user()->id)
                        ->first();

                    if (!$booking) {
                        $fail('Booking not found');
                    }

                    if ($booking->status != BookingStatus::APPROVED) {
                        $fail('Booking is not active');
                    }

                    $extendTime= Carbon::parse($value);
                    $endDateTime = Carbon::parse($booking->end_date);

                    if ($endDateTime->greaterThan($extendTime)) {
                        $fail('Extend time must be less than end time');
                    }

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
