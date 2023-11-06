<?php

namespace App\Http\Requests\Api\HelpCall;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Enums\HelpCallStatus;
use App\Enums\UserRole;

class UpdateHelpCallRequest extends FormRequest
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
            'supporterId' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    $admin = User::where('id', $value)
                        ->where('type', UserRole::ADMIN)
                        ->where('client_id', user()->client_id)
                        ->first();
                    if (!$admin) {
                        $fail('Supporter not found');
                    }
                }
            ],
            'status' => 'required|integer|in:' . implode(',', array_keys(HelpCallStatus::getAllStatuses())),
        ];
    }
}
