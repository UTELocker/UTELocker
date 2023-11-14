<?php

namespace App\Http\Requests\Api\Bookings;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
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
            'id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $booking = Booking::where('id', $value)->first();
                    if (!$booking) {
                        $fail('Booking not found');
                        return;
                    }
                    $idUsers = user()->id;
                    if ($booking->owner_id != $idUsers) {
                        $fail('Booking not found');
                        return;
                    }
                    if ($booking->status != BookingStatus::APPROVED && $booking->status != BookingStatus::EXPIRED) {
                        $fail('Booking not approved');
                    }
                },
            ],
            'oldPassword' => [
                'required',
                'string',
            ],
        ];
    }
}
