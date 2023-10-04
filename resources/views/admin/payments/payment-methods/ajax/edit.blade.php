<div class="row">
    <div class="col-sm-12">
        <x-form id="save-payment-method-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{ __('modules.paymentMethod.details') }}
                </h4>
                <div class="row p-20">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="code"
                                    :fieldLabel="__('modules.paymentMethod.code')"
                                    fieldName="code"
                                    fieldRequired="true"
                                    field-read-only="true"
                                    :fieldPlaceholder="__('modules.paymentMethod.placeholders.code')"
                                    :fieldValue="$paymentMethod->code ?? ''">
                                </x-forms.text>
                            </div>
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="name"
                                    :fieldLabel="__('modules.paymentMethod.name')"
                                    fieldName="name"
                                    :fieldPlaceholder="__('modules.paymentMethod.placeholders.name')"
                                    fieldRequired="true"
                                    :fieldValue="$paymentMethod->name ?? ''">
                                </x-forms.text>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.select
                                    fieldId="type"
                                    :fieldLabel="__('modules.paymentMethod.type')"
                                    fieldRequired="true"
                                    :disabled="true"
                                    fieldName="type">
                                    @foreach(
                                        \App\Enums\PaymentMethodType::getDescriptions(
                                            \App\Enums\PaymentMethodType::getNotAvailableTypes()
                                        )
                                        as $key => $type
                                    )
                                        @if($paymentMethod->type == $key)
                                            <option value="{{ $key }}" selected>{{ $type }}</option>
                                        @endif
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="col-md-6">
                                <x-forms.select
                                    fieldId="active"
                                    :fieldLabel="__('app.active')"
                                    fieldRequired="true"
                                    fieldName="active">
                                    @foreach(
                                        \App\Classes\Common::getYesNoOptions() as $option
                                    )
                                        <option
                                            value="{{ $option['value'] }}"
                                            {{ $paymentMethod->active == $option['value'] ? 'selected' : '' }}
                                        >{{ $option['text'] }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>

                    </div>
                </div>
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{ __('modules.paymentMethod.configs') }}
                </h4>
                <div class="row p-20">
                    <div class="col-lg-12">
                        @switch($paymentMethod->type)
                            @case(\App\Enums\PaymentMethodType::CASH)
                                @include('admin.payments.payment-methods.configs.cash')
                            @case(\App\Enums\PaymentMethodType::BANK_TRANSFER)
                        @endswitch
                    </div>
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-payment-method-form"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel
                        :link="route('admin.payment.methods.index')"
                        class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-forms.actions>
            </div>
        </x-form>
    </div>
</div>

<script>
    $(document).ready(function() {
        UTELocker.common.init(RIGHT_MODAL);
    });

    $('#save-payment-method-form').click(function() {
        const url = "{{ route('admin.payment.methods.store') }}";
        const data = $('#save-payment-method-data-form').serialize();

        savePaymentMethod(data, url, '#save-payment-method-form');
    });

    function savePaymentMethod(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#save-payment-method-data-form',
            type: "POST",
            disableButton: buttonSelector,
            data: data,
            success: function(response) {
                if (response.status === 'success') {
                    if ($(MODAL_XL).hasClass('show')) {
                        $(MODAL_XL).modal('hide');
                        window.location.reload();
                    } else if(typeof response.redirectUrl !== 'undefined'){
                        window.location.href = response.redirectUrl;
                    }
                }
            }
        })
    }
</script>
