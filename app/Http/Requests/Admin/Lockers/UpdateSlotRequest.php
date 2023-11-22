<?php

namespace App\Http\Requests\Admin\Lockers;

use App\Enums\LockerSlotStatus;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LockerSlotType;
use App\Models\LockerSlot;

class UpdateSlotRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [];
        $lockerSlotId = $this->route('slot');
        $lockerSlot = LockerSlot::find($lockerSlotId);
        if ($lockerSlot->type === LockerSlotType::SLOT) {
            $rules = [
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|int|in:' . implode(',', array_keys(LockerSlotStatus::getDescriptions())),
            ];
        } else {
            $rules = [
                'price' => 'required|numeric|min:0',
                'hours' => 'required|numeric|min:0',
                'prefix' => 'required|string|max:10',
                'bufferTime' => 'required|numeric|min:0|max:180',
                'maxBookings' => 'required|numeric|min:0',
            ];
        }
        return $rules;
    }
}
