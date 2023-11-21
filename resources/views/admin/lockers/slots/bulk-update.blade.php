<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">{{ $pageTitle }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<x-form
    id="slotConfigForm"
    method="PUT"
    class="ajax-form"
    action="{{ route('admin.lockers.slots.update', ['locker' => $lockerId, 'slot' => $slot->id]) }}"
>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="modal-body">
            <div class="portlet-body">
                    @if($slot->type == \App\Enums\LockerSlotType::SLOT)
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
                        <div class="row" id="row_cancel_reason"
                            style="{{ $slot->status == \App\Enums\LockerSlotStatus::AVAILABLE ? 'display: none' : '' }}"
                        >
                            <div class="col-md-12">
                                    <x-forms.text
                                        fieldId="cancel_reason"
                                        :fieldLabel="__('modules.bookings.cancelReason')"
                                        fieldName="cancel_reason"
                                        fieldRequired="false"
                                        :fieldPlaceholder="__('placeholders.cancelReason')"
                                        :fieldValue="''">
                                    </x-forms.text>
                                </div>
                        </div>
                    @endif
                    @if($slot->type == \App\Enums\LockerSlotType::CPU)
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label">@lang('settings.maxTimeHoursBooking')</label>
                                <input type="number" name="hours" class="form-control"
                                       value="{{ $slot?->hours }}" min="0"
                                       placeholder="@lang('settings.maxTimeHoursBookingPlaceholder')">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label">@lang('settings.prefix')</label>
                                <input type="text" name="prefix" class="form-control" value="{{ $slot?->prefix }}"
                                    placeholder="@lang('settings.prefixPlaceholder')">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label">@lang('settings.maxBookings')</label>
                                <input type="number" name="maxBookings" class="form-control"
                                    value="{{ $slot?->maxBookings }}" min="0"
                                    placeholder="@lang('settings.maxBookingsPlaceholder')">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label">@lang('settings.bufferTime')</label>
                                <input type="number" name="bufferTime" class="form-control"
                                    placeholder="@lang('settings.bufferTimePlaceholder')"
                                    value={{ $slot?->bufferTime ?? 30}}>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label class="control-label">@lang('settings.price')</label>
                            <input type="number" name="price" class="form-control"
                                value="{{ $slot?->price }}" min="0"
                                placeholder="@lang('settings.pricePlaceholder')">
                        </div>
                    </div>
            </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
        <x-forms.button-primary id="save-slot-config" icon="save" type="submit" >
            @lang('app.save')
        </x-forms.button-primary>
    </div>
</x-form>

<script>

    $('#status').on('change', function() {
        if ($(this).val() == '{{ \App\Enums\LockerSlotStatus::AVAILABLE }}') {
            $('#cancel_reason').val('');
            $('#row_cancel_reason').hide();
        } else {
            Swal.fire({
                title: 'Are you sure to change status?',
                text: 'If you change status to Locked or Booked, all bookings in slot will be canceled.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('app.confirm') }}',
                cancelButtonText: '{{ __('app.cancel') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#row_cancel_reason').show();
                } else {
                    $('#status').val('{{ \App\Enums\LockerSlotStatus::AVAILABLE }}');
                    $('#status').trigger('change');
                }
            });
        }
    });

    $(document).ready(function() {
        UTELocker.common.init(MODAL_LG);

        const url = "{{ route('admin.lockers.slots.show', ['locker' => $lockerId, 'slot' => $slot->id]) }}";
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                if (response.status === "success") {
                    const data = response.data.slot;
                    if (data.type == "{{ \App\Enums\LockerSlotType::CPU }}") {
                        if (data.config != null) {
                            $config = JSON.parse(data.config);
                            $('#slotConfigForm').find('input[name="hours"]').val($config.hours);
                            $('#slotConfigForm').find('input[name="prefix"]').val($config.prefix);
                            $('#slotConfigForm').find('input[name="price"]').val($config.price);
                            $('#slotConfigForm').find('input[name="bufferTime"]').val($config.bufferTime);
                            $('#slotConfigForm').find('input[name="maxBookings"]').val($config.maxBookings);
                        }
                    }
                    if (data.type == "{{ \App\Enums\LockerSlotType::SLOT }}") {
                        $('#slotConfigForm').find('select[name="status"]').val(data.status);
                        if (data.config != null) {
                            $config = JSON.parse(data.config);
                            $('#slotConfigForm').find('input[name="price"]').val($config.price);
                        }
                    }
                }
            }
        });
    });

</script>
