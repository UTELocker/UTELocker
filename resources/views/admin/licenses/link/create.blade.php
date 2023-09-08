<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">{{ $pageTitle }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="licenseLinkForm" method="POST" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.text
                            fieldId="code"
                            :fieldLabel="__('app.code')"
                            fieldRequired="true"
                            fieldName="code"
                            fieldPlaceholder="LIC-2023-0000-0000-0000"
                            :fieldValue="''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-6">
                        <x-forms.select
                            fieldId="client_id"
                            fieldLabel="Client"
                            fieldName="client_id"
                            fieldRequired="true"
                        >
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
    <x-forms.button-primary id="save-location-type" icon="link">@lang('app.link')</x-forms.button-primary>
</div>

<script>
    $(document).ready(function() {
        $('#save-location-type').click(function() {
            const url = "{{ route('admin.licenses.link.store') }}";
            const data = $('#licenseLinkForm').serialize();

            $.easyAjax({
                type: 'POST',
                url: url,
                data: data,
                container: '#licenseLinkForm',
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
