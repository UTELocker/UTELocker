<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <x-forms.label
                fieldId="config_detail"
                :field-required="true"
                :fieldLabel="__('modules.paymentMethod.configFieldset.information')">
            </x-forms.label>
            <div id="config_detail_id">
                {!!
                    $paymentMethodConfig[\App\Libs\PaymentMethodConfig\CashPaymentMethodConfig::CASH_PMC_DETAILS]
                !!}
            </div>
            <textarea name="config_detail" id="config_detail_id-text" class="d-none"></textarea>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        UTELocker.common.initQuill('#config_detail_id');
        quillIdArr.push('#config_detail_id');
    });
</script>
