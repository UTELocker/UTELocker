<?php

namespace App\Http\Requests\Admin\Licenses;

use Illuminate\Foundation\Http\FormRequest;

class LinkLicenseRequest extends FormRequest
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
            'code' => 'required|exists:licenses,code,client_id,NULL',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'License code is required',
            'code.exists' => 'License code does not exist or is already linked to a client',
            'client_id.required' => 'Client is required',
            'client_id.exists' => 'Client does not exist',
        ];
    }
}
