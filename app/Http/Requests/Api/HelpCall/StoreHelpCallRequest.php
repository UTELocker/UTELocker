<?php

namespace App\Http\Requests\Api\HelpCall;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\HelpCallType;

class StoreHelpCallRequest extends FormRequest
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
            "type" => [
                "required",
                "integer",
                "in:" . implode(",", HelpCallType::getAll()),
                function ($attribute, $value, $fail) {
                    if ($value >= HelpCallType::LOCKER && !$this->has("lockerId")) {
                        $fail("The lockerId field is required.");
                    }
                    if ($value >= HelpCallType::LOCKER_SLOT && !$this->get("lockerSlotId")) {
                        $fail("The lockerSlotId field is required.");
                    }

                    if ($value >= HelpCallType::BOOKING && !$this->has("bookingId")) {
                        $fail("The bookingId field is required.");
                    }
                },
            ],
            "title" => "required|string",
            "content" => "required|string",
            "attachment",
        ];
    }
}
