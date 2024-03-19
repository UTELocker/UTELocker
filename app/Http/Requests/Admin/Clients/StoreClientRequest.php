<?php

namespace App\Http\Requests\Admin\Clients;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends BaseRequest
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
     * @return array
     */
    public function rules(): array
    {
        $userRules = [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_password' => 'nullable|required|min:8',
            'user_mobile' => 'nullable|numeric',
            'user_gender' => 'required|int|in:0,1,2',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_locale' => 'required|string|in:en,vi',
        ];

        $clientRules = [
            'client_name' => 'required',
            'client_email' => 'required|email',
            'client_website' => 'nullable|url',
            'client_phone' => 'nullable|numeric',
            'client_address' => 'required|string',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        return array_merge($userRules, $clientRules);
    }
}
