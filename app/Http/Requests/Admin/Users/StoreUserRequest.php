<?php

namespace App\Http\Requests\Admin\Users;

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
        return [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_password' => 'nullable|required|min:8',
            'user_mobile' => 'nullable|numeric',
            'user_gender' => 'required|int|in:0,1,2',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_locale' => 'required|string|in:en,vi',
            'user_type' => 'required|int|in:0,1,2',
            'user_client_id' => 'required|exists:clients,id'
        ];
    }
}
