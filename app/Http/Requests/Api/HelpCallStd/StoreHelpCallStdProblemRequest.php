<?php

namespace App\Http\Requests\Api\HelpCallStd;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\HelpCallType;

class StoreHelpCallStdProblemRequest extends FormRequest
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
            'type' => [
                'required',
                'in:' . implode(',', HelpCallType::getAll())
            ],
            'description' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }
}
