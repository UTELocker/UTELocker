<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.text
                fieldId="app_id"
                :fieldLabel="__('modules.paymentMethod.configFieldset.app_id')"
                fieldName="{{ \App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::APP_ID }}"
                fieldRequired="true"
                fieldPlaceholder="1234"
                fieldValue="{{
                    $paymentMethodConfig->getConfigs()
                    [\App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::APP_ID]
                }}"
            >
            </x-forms.text>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.text
                fieldId="key_1"
                :fieldLabel="__('modules.paymentMethod.configFieldset.secret_key')"
                fieldName="{{ \App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::KEY_1 }}"
                fieldRequired="true"
                fieldPlaceholder="sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn"
                fieldValue="{{
                    $paymentMethodConfig->getConfigs()
                    [\App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::KEY_1]
                }}"
            >
            </x-forms.text>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.text
                fieldId="key_1"
                :fieldLabel="__('modules.paymentMethod.configFieldset.secret_key')"
                fieldName="{{ \App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::KEY_2 }}"
                fieldRequired="true"
                fieldPlaceholder="trMrHtvjo6myautxDUiAcYsVtaeQ8nhf"
                fieldValue="{{
                    $paymentMethodConfig->getConfigs()
                    [\App\Libs\PaymentMethodConfig\ZaloPayPaymentMethodConfig::KEY_2]
                }}"
            >
            </x-forms.text>
        </div>
    </div>
</div>
