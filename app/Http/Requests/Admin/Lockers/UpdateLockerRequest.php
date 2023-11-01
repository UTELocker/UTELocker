<?php

namespace App\Http\Requests\Admin\Lockers;

use App\Enums\LockerStatus;
use App\Models\Locker;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLockerRequest extends FormRequest
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
            'date_of_manufacture' => 'required|date_format:' . globalSettings()->date_format,
            'status' => 'required|in:' . implode(',', array_keys(
                    LockerStatus::getDescriptions([LockerStatus::IN_USE]))),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location_id' => 'required|exists:locations,id',
            'code' => 'required|string|max:255|unique:lockers,code,' . $this->route('locker') . ',id',
        ];
    }
}
