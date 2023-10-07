<?php

namespace App\Http\Requests\Admin\Payments;

use App\Enums\PaymentMethodType;
use App\Libs\PaymentMethodConfig\BankTransferPaymentMethodConfig;
use App\Libs\PaymentMethodConfig\CashPaymentMethodConfig;
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
                $rules[CashPaymentMethodConfig::CASH_PMC_DETAILS] = 'required|string';
                break;
            case PaymentMethodType::BANK_TRANSFER:
                $rules[BankTransferPaymentMethodConfig::BANK_TRANSFER_PMC_DETAILS] = 'required|string';
                $rules[BankTransferPaymentMethodConfig::BANK_TRANSFER_PMC_QR_CODE] = 'required|string';
                break;
            default:
                break;
        }

        return $rules;
    }
}
