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
    <div class="modal-body">
            <div class="portlet-body">
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
                    @if($slot->type == \App\Enums\LockerSlotType::CPU)
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label m-0 mt-10">@lang('settings.dateTimeLimit')</label>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">@lang('settings.days')</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="days" class="form-control"
                                            value="{{ $slot?->days ?? 0 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">@lang('settings.hours')</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="number" name="hours" class="form-control"
                                            value="{{ $slot?->hours ?? 0 }}" min="0" max="23">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">@lang('settings.minutes')</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="number" name="minutes" class="form-control"
                                            value="{{ $slot?->minutes ?? 0 }}" min="0" max="59">
                                    </div>
                                </div>
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
                                <label class="control-label">@lang('settings.defaultPrice')</label>
                                <input type="number" name="price" class="form-control" value="{{ $slot?->price }}" min="0"
                                    placeholder="@lang('settings.pricePlaceholder')">
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
                    @if($slot->type == \App\Enums\LockerSlotType::SLOT)
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="control-label">@lang('settings.price')</label>
                                <input type="number" name="price" class="form-control" value="{{ $slot?->price }}" min="0"
                                    placeholder="@lang('settings.pricePlaceholder')">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
        <x-forms.button-primary id="save-slot-config" icon="save" type="submit" >@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>
    $(document).ready(function() {
        UTELocker.common.init(MODAL_LG);

        const url = "{{ route('admin.lockers.slots.show', ['locker' => $lockerId, 'slot' => $slot->id]) }}";
        $.easyAjax({
            type: 'GET',
            url: url,
            success: function(response) {
                if (response.status === "success") {
                    const data = response.data.slot;
                    if (data.type == "{{ \App\Enums\LockerSlotType::CPU }}") {
                        if (data.config != null) {
                            $config = JSON.parse(data.config);
                            $('#slotConfigForm').find('input[name="days"]').val($config.days);
                            $('#slotConfigForm').find('input[name="hours"]').val($config.hours);
                            $('#slotConfigForm').find('input[name="minutes"]').val($config.minutes);
                            $('#slotConfigForm').find('input[name="prefix"]').val($config.prefix);
                            $('#slotConfigForm').find('input[name="price"]').val($config.price);
                            $('#slotConfigForm').find('input[name="bufferTime"]').val($config.bufferTime);
                        }
                    }
                    if (data.type == "{{ \App\Enums\LockerSlotType::SLOT }}") {
                        $('#slotConfigForm').find('select[name="status"]').val(data.status);
                        if (data.config != null) {
                            $config = JSON.parse(data.config);
                            $('#slotConfigForm').find('input[name="price"]').val($config.price);
                        }
                    }
                    const bookingActive = response.data.bookingActive;
                    if (bookingActive !== null && data.type !== "{{ \App\Enums\LockerSlotType::CPU }}") {
                        let html = `<div class="alert alert-danger" role="alert">
                                        <strong>@lang('settings.warning')</strong> @lang('settings.slotHasBooking')
                                    </div>`;
                        $('#slotConfigForm').find('.modal-body').prepend(html);
                    }
                }
            }
        });
    });

</script>
