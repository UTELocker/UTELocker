<?php

namespace App\Http\Requests\Admin\Payments;

use App\Enums\PaymentMethodType;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodRequest extends FormRequest
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
        $rules = [
            'name' => 'required|min:3|max:50',
            'active' => 'required|in:Y,N',
        ];

        $paymentMethod = PaymentMethod::findOrFail($this->route('method'));

        switch ($paymentMethod->type) {
            case PaymentMethodType::CASH:
                $rules['config_detail'] = 'required|string';
                break;
            default:
                break;
        }

        return $rules;
    }
}
