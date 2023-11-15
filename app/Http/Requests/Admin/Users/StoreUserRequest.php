<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'user_name' => 'required',
            'user_email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)
                        ->when(!user()->isSuperUser(), function ($query) {
                            $query->where('client_id', user()->client_id);
                        })
                        ->when(user()->isSuperUser(), function ($query) {
                            $query->where('client_id', $this->user_client_id);
                        })
                        ->first();
                    if ($user) {
                        $fail('Email already exists in the site group');
                    }
                },
            ],
            'user_password' => 'nullable|required|min:8',
            'user_mobile' => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:11',
                function ($attribute, $value, $fail) {
                    $user = User::where('mobile', $value)
                        ->when(!user()->isSuperUser(), function ($query) {
                            $query->where('client_id', user()->client_id);
                        })
                        ->when(user()->isSuperUser(), function ($query) {
                            $query->where('client_id', $this->user_client_id);
                        })
                        ->first();
                    if ($user) {
                        $fail('Mobile already exists in the site group');
                    }
                },
            ],
            'user_gender' => 'required|int|in:0,1,2',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_locale' => 'required|string|in:en,vi',
            'user_type' => 'required|int|in:0,1,2',
        ];

        if (User::hasPermission(UserRole::SUPER_USER)) {
            $rules['user_client_id'] = 'required|exists:clients,id';
        }

        return $rules;
    }
}
