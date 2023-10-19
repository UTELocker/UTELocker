<?php

namespace App\Http\Requests\Api\Bookings;

use App\Enums\BookingStatus;
use App\Models\LockerSlot;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreBookingRequest extends FormRequest
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
            'start_date' => 'required|date_format:Y-m-d H:i|after:now',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'list_slots_id' => 'required|array',
            'list_slots_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:locker_slots,id',
                function ($attribute, $value, $fail) {
                    $startDate = $this->input('start_date');
                    $endDate = $this->input('end_date');

                    $slot = LockerSlot::where('locker_slots.id', $value)
                        ->leftJoin('bookings', 'bookings.locker_slot_id', '=', 'locker_slots.id')
                        ->where(function ($q) {
                            $q->where('bookings.status', BookingStatus::PENDING)
                                ->orWhere('bookings.status', BookingStatus::APPROVED);
                        })
                        ->where(function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('bookings.start_date', [$startDate, $endDate])
                                ->orWhereBetween('bookings.end_date', [$startDate, $endDate]);
                        })
                        ->first();

                    if ($slot) {
                        $fail('Slot ' . $slot->row . '-' . $slot->column . ' is not available');
                    }
                },
            ],
        ];
    }

}
