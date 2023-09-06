<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">{{ $pageTitle }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="locationTypeForm" method="POST" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.text
                            fieldId="code"
                            :fieldLabel="__('app.code')"
                            fieldRequired="true"
                            fieldName="code"
                            :fieldPlaceholder="__('placeholders.code')"
                            :fieldValue="''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-6">
                        <x-forms.select
                            fieldId="client_id"
                            fieldLabel="Client"
                            fieldName="client_id"
                        >
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-12">
                        <x-forms.text
                            fieldId="description"
                            :fieldLabel="__('app.description')"
                            fieldName="description"
                            :fieldPlaceholder="__('placeholders.description')"
                            :fieldValue="''">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
    <x-forms.button-primary id="save-location-type" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    $(document).ready(function() {
        $('#save-location-type').click(function() {
            const url = "{{ route('admin.location.types.store') }}";
            const data = $('#locationTypeForm').serialize();

            $.easyAjax({
                type: 'POST',
                url: url,
                data: data,
                container: '#locationTypeForm',
                success: function(response) {
                    if (response.status == "success") {
                        window.location.reload();
                    }
                }
            });
        });

        UTELocker.common.init(MODAL_LG);
    });
</script>
