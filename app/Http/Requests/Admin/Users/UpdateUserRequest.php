<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

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
        $userId = $this->route('user');
        $rules = [
            'user_name' => 'required',
            'user_email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($userId) {
                    $user = User::where('email', $value)
                        ->when(!user()->isSuperUser(), function ($query) {
                            $query->where('client_id', user()->client_id);
                        })
                        ->when(user()->isSuperUser(), function ($query) {
                            $query->where('client_id', $this->user_client_id);
                        })
                        ->first();
                    if ($user && $user->id != $userId) {
                        $fail('Email already exists in the site group');
                    }
                },
            ],
            'user_password' => 'nullable|min:8',
            'user_mobile' => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:11',
                function ($attribute, $value, $fail) use ($userId) {
                    $user = User::where('mobile', $value)
                        ->when(!user()->isSuperUser(), function ($query) {
                            $query->where('client_id', user()->client_id);
                        })
                        ->when(user()->isSuperUser(), function ($query) {
                            $query->where('client_id', $this->user_client_id);
                        })
                        ->first();
                    if ($user && $user->id != $userId) {
                        $fail('Mobile already exists in the site group');
                    }
                },
            ],
            'user_gender' => 'required|int|in:0,1,2',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_locale' => 'required|string|in:en,vi',
            'user_type' => [
                'required',
                'int',
                'in:' . implode(',', UserRole::getValues()),
                function ($attribute, $value, $fail) {
                    if ($value == UserRole::SUPER_USER && !User::hasPermission(UserRole::SUPER_USER)) {
                        $fail('You do not have permission to create super user');
                    }
                }
            ]
        ];
        if (User::hasPermission(    UserRole::SUPER_USER)) {
            $rules['user_client_id'] = 'required|exists:clients,id';
        }
        return $rules;
    }
}
