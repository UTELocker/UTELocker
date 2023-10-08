<?php

namespace App\Http\Requests\Admin\Settings;

use App\Models\GlobalSetting;
use Illuminate\Foundation\Http\FormRequest;
use DateTimeZone;

class UpdateSettingRequest extends FormRequest
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
            'date_format' => 'required|in:' .  implode(',', array_keys(GlobalSetting::DATE_FORMATS)),
            'time_format' => 'required|in:' . implode(',', array_keys(GlobalSetting::TIME_FORMATS)),
            'locale' => 'required|string|in:en,vi',
            'timezone' => 'required|in:' . implode(',', array_values(DateTimeZone::listIdentifiers()))
        ];
    }
}
