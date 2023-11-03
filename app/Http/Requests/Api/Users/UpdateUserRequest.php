<?php

namespace App\Http\Requests\Api\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $rules = [
            'name' => 'nullable',
            'email' => [
                'nullable',
                'email',
                function ($attribute, $value, $fail) {
                    $isUniqueEmailInClient = User::where('email', $value)
                        ->where('client_id', '==', auth()->user()->client_id)
                        ->first();
                    if ($isUniqueEmailInClient) {
                        $fail('This email is already taken.');
                    }
                }
            ],
            'mobile' => 'nullable|numeric|digits_between:10,11',
            'gender' => 'nullable|int|in:0,1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'locale' => 'nullable|string|in:en,vi',
            'type' => 'nullable|int|in:0,1,2',
        ];
        if ($this->has('password')) {
            $rules['old_password'] = 'required|string';
            $rules['password_confirmation'] = 'required|string|same:password';
            $rules['password'] = 'required|string';
        }
        if ($this->has('password_is2FA')) {
            $rules['old_password_is2FA'] = 'required|string|max:6|min:6';
            $rules['password_is2FA_confirmation'] = 'required|string|max:6|min:6|same:password_is2FA';
            $rules['password_is2FA'] = 'required|string|min:6|max:6';
        }
        return $rules;
    }
}
