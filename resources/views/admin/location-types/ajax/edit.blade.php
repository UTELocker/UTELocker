<div class="row">
    <div class="col-sm-12">
        <x-form id="locationTypeForm">
            @method('PUT')
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{ __('modules.paymentMethod.details') }}
                </h4>
            <x-form id="locationTypeForm" method="POST" class="ajax-form">
                <input type="hidden" name="id" value="{{ $locationType->id }}">
                <div class="form-body">
                    <div class="row p-20">
                        <div class="col-md-6">
                            <x-forms.text
                                fieldId="code"
                                :fieldLabel="__('app.code')"
                                fieldRequired="true"
                                fieldName="code"
                                :fieldPlaceholder="__('placeholders.code')"
                                :fieldValue="$locationType->code ?? ''">
                            </x-forms.text>
                        </div>
                        <div class="col-md-6">
                            <x-forms.select
                                fieldId="client_id"
                                fieldLabel="Client"
                                fieldName="client_id"
                            >
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        @if ($client->id == $locationType->client_id)
                                            selected
                                       @endif
                                    >{{ $client->name }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                        <div class="col-md-12">
                            <x-forms.text
                                fieldId="description"
                                :fieldLabel="__('app.description')"
                                fieldName="description"
                                :fieldPlaceholder="__('placeholders.description')"
                                :fieldValue="$locationType->description ?? ''">
                            </x-forms.text>
                        </div>
                    </div>
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-location-type"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel
                        :link="route('admin.location.types.index')"
                        class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-forms.actions>
            </x-form>
            </div>
        </x-form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#save-location-type').click(function() {
            const url = "{{ route('admin.location.types.update', $locationType->id) }}";
            const data = $('#locationTypeForm').serialize();

            $.easyAjax({
                type: 'POST',
                url: url,
                data: data,
                container: '#locationTypeForm',
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        UTELocker.common.init(MODAL_LG);
    });
</script>
