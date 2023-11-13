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
            'date_format' => 'required|sometimes|in:' .  implode(',', array_keys(GlobalSetting::DATE_FORMATS)),
            'time_format' => 'required|sometimes|in:' . implode(',', array_keys(GlobalSetting::TIME_FORMATS)),
            'locale' => 'required|sometimes|string|in:en,vi',
            'timezone' => 'required|sometimes|in:' . implode(',', array_values(DateTimeZone::listIdentifiers())),
            'pusher_app_id' => 'required|sometimes',
            'pusher_app_key' => 'required|sometimes',
            'pusher_app_secret' => 'required|sometimes',
            'pusher_app_cluster' => 'required|sometimes',
            'refund_soon_cancel_booking' => 'required|sometimes|numeric|min:0|max:100'
        ];
    }
}
