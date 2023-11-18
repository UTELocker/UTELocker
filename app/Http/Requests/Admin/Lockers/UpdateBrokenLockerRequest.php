<?php

namespace App\Http\Requests\Admin\Lockers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrokenLockerRequest extends FormRequest
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
            'locker_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $locker = \App\Models\Locker::find($value);
                    if (!$locker) {
                        $fail('The locker does not exist');
                    }
                    if ($locker->status != \App\Enums\LockerStatus::PENDING_BROKEN) {
                        $fail('The locker is not pending broken');
                    }
                },
            ],
            'status' => [
                'required',
                'in:' . implode(',', [
                    \App\Enums\LockerStatus::BROKEN,
                    \App\Enums\LockerStatus::UNDER_MAINTENANCE,
                    \App\Enums\LockerStatus::IN_USE,
                ]),
            ],
        ];
    }

    // merge id from route
    protected function prepareForValidation()
    {
        $this->merge([
            'locker_id' => $this->route('broken_locker'),
        ]);
    }
}
