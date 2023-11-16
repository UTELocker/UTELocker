<?php

namespace App\Http\Requests\Api\Bookings;

use App\Enums\BookingStatus;
use App\Models\LockerSlot;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Enums\LockerSlotType;
use Carbon\Carbon;
use App\Enums\LockerSlotStatus;

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
        $listSlotsId = $this->input('list_slots_id');
        $lockerCPU = LockerSlot::where('type', LockerSlotType::CPU)
            ->where('locker_id', function ($query) use ($listSlotsId) {
                $query->select('locker_id')
                ->from('locker_slots')
                ->where('id', $listSlotsId[0]);
            })
            ->select('config')
            ->first();
        $configLocker = json_decode($lockerCPU->config, true);
        $startDate = Carbon::parse($this->input('start_date'))
            ->subMinutes($configLocker['bufferTime'] ?? 30)
            ->toDateTimeString();
        $endDate = Carbon::parse($this->input('end_date'))
            ->addMinutes($configLocker['bufferTime'] ?? 30)
            ->toDateTimeString();
        $slot = LockerSlot::leftJoin('bookings', 'bookings.locker_slot_id', '=', 'locker_slots.id')
            ->whereIn('locker_slots.id', $listSlotsId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('locker_slots.status', LockerSlotStatus::LOCKED)
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where(function ($q) {
                            $q->where('bookings.status', BookingStatus::PENDING)
                                ->orWhere('bookings.status', BookingStatus::APPROVED);
                        })
                        ->where(function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('bookings.start_date', [$startDate, $endDate])
                                ->orWhereBetween('bookings.end_date', [$startDate, $endDate]);
                        });
                    });
            })
            ->get();
        if ($slot->count() > 0) {
            throw ValidationException::withMessages([
                'list_slots_id' => 'Slot ' . $slot[0]->row . '-' . $slot[0]->column . ' is not available',
            ]);
        }
        return [
            'start_date' => 'required|date_format:Y-m-d H:i|after:now',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'list_slots_id' => 'required|array',
            'list_slots_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:locker_slots,id',
            ],
        ];
    }

}
