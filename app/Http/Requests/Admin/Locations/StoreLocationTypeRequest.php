<?php

namespace App\Http\Requests\Admin\Locations;

use App\Models\LocationType;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocationTypeRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'code' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (LocationType::where('code', $value)
                        ->where('client_id', $this->get('client_id'))
                        ->when(!empty($this->get('id')), function ($query, $locationType) {
                            $query->whereNotIn('id', [$this->get('id')]);
                        })
                        ->exists()
                    ) {
                        $fail(__('validation.unique', ['attribute' => $attribute]));
                    }
                },
            ],
            'description' => 'nullable|string|max:255',
        ];
    }
}
