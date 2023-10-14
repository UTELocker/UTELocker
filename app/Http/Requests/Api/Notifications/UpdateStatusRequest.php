<?php

namespace App\Http\Requests\Api\Notifications;

use App\Models\Notification;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
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
            'id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $userId = \auth()->user()->id;
                    $notification = Notification::where('id', $value)->where('owner_id', $userId)->first();
                    if (!$notification) {
                        $fail('Notification not found');
                    }
                }
            ]
        ];
    }

    // merge $id in route to request
    public function validationData(): array
    {
        return array_merge($this->request->all(), [
            'id' => $this->route('id'),
        ]);
    }
}
