<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.text
                fieldId="terminal_id"
                :fieldLabel="__('modules.paymentMethod.configFieldset.terminal_id')"
                fieldName="{{ \App\Libs\PaymentMethodConfig\VNPayPaymentMethodConfig::TERMINAL_ID }}"
                fieldRequired="true"
                fieldPlaceholder="NT123456"
                fieldValue="{{
                    $paymentMethodConfig->getConfigs()
                    [\App\Libs\PaymentMethodConfig\VNPayPaymentMethodConfig::TERMINAL_ID]
                }}"
            >
            </x-forms.text>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.text
                fieldId="secret_key_id"
                :fieldLabel="__('modules.paymentMethod.configFieldset.secret_key')"
                fieldName="{{ \App\Libs\PaymentMethodConfig\VNPayPaymentMethodConfig::SECRET_KEY }}"
                fieldRequired="true"
                fieldPlaceholder="ANFASDNXASKDQWNDAMDAS12312ASD"
                fieldValue="{{
                    $paymentMethodConfig->getConfigs()
                    [\App\Libs\PaymentMethodConfig\VNPayPaymentMethodConfig::SECRET_KEY]
                }}"
            >
            </x-forms.text>
        </div>
    </div>
</div>
