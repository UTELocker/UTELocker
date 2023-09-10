<?php

namespace App\Http\Requests\Admin\Payments;

use App\Enums\PaymentMethodType;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
            'code' => 'required|unique:payment_methods,code,client_id' . user()->client_id,
            'name' => 'required',
            'type' => 'required|in:' . implode(',', array_keys(
                PaymentMethodType::getDescriptions(PaymentMethodType::getNotAvailableTypes()))),
        ];
    }
}
