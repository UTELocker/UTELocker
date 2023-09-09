<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">{{ $pageTitle }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="slotConfigForm" method="PUT" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <x-forms.select fieldId="status"
                                        :fieldLabel="__('app.status')"
                                        :field
                                        fieldName="status">
                            @foreach(\App\Enums\LockerSlotStatus::getDescriptions() as $key => $status)
                                <option
                                    value="{{ $key }}"
                                    {{ $key == $slot->status ? 'selected' : '' }}
                                >{{ $status }}</option>
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
    <x-forms.button-primary id="save-slot-config" icon="save">@lang('app.save')</x-forms.button-primary>
</div>

<script>
    $(document).ready(function() {
        UTELocker.common.init(MODAL_LG);
    });

    $('#save-slot-config').click(function() {
        const url = "{{ route('admin.lockers.slots.update', ['locker' => $lockerId, 'slot' => $slot->id]) }}";
        const data = $('#slotConfigForm').serialize();

        $.easyAjax({
            type: 'POST',
            url: url,
            data: data,
            container: '#slotConfigForm',
            success: function(response) {
                if (response.status === "success") {
                    window.location.reload();
                }
            }
        });
    });
</script>
