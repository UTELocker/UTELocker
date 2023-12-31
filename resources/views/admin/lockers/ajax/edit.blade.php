<div class="row">
    <div class="col-sm-12">
        <x-form id="save-locker-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{ __('modules.lockers.details') }}
                </h4>
                <div class="row p-20">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-12">
                                <x-forms.text
                                    fieldId="code"
                                    :fieldLabel="__('modules.lockers.code')"
                                    fieldName="code"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.code')"
                                    :fieldValue="$locker->code ?? ''">
                                </x-forms.text>
                            </div>
                            <div class="col-md-12">
                                <x-forms.text
                                    fieldId="description"
                                    :fieldLabel="__('modules.lockers.description')"
                                    fieldName="description"
                                    :fieldPlaceholder="__('modules.lockers.placeholders.description')"
                                    :fieldValue="$locker->description ?? ''">
                                </x-forms.text>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.date-picker
                                    fieldId="date_of_manufacture"
                                    :fieldLabel="__('modules.lockers.dateOfManufacture')"
                                    fieldName="date_of_manufacture"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.code')"
                                    :fieldValue="date(globalSettings()->date_format,strtotime($locker->date_of_manufacture)) ?? ''">
                                </x-forms.date-picker>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="status" :fieldLabel="__('modules.lockers.status')"
                                                fieldName="status"
                                                fieldRequired="true"
                                >
                                    @php

                                        $statusesExcute = $locker->status == \App\Enums\LockerStatus::AVAILABLE
                                        ? [
                                            \App\Enums\LockerStatus::UNDER_MAINTENANCE,
                                            \App\Enums\LockerStatus::PENDING_BROKEN,
                                            \App\Enums\LockerStatus::AVAILABLE,
                                            \App\Enums\LockerStatus::BROKEN,
                                        ]
                                        : [
                                            \App\Enums\LockerStatus::AVAILABLE,
                                            \App\Enums\LockerStatus::PENDING_BROKEN,
                                        ]
                                    @endphp
                                    @foreach(
                                        \App\Enums\LockerStatus::getDescriptions($statusesExcute)
                                        as $key => $status
                                    )
                                        <option @if ($key == $locker->status) selected @endif value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="location_id" :fieldLabel="__('modules.locations.title')"
                                                fieldName="location_id">
                                    @foreach ($locations as $key => $value)
                                        <option @if ($key == $locker->location_id) selected @endif value="{{ $key }}">
                                            <h5 class="mb-0 f-12 text-darkest-grey">
                                                {{ $value['code'] }} - {{ $value['description'] }}
                                            </h5>
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>
                        <div class="row" id="row_cancel_reason"
                            @if ($locker->status == \App\Enums\LockerStatus::AVAILABLE) style="display: none;" @endif
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
                    </div>
                    <div class="col-lg-4">
                        <x-forms.file
                            allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.lockers.image')"
                            fieldName="image"
                            fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')"/>
                    </div>
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-locker-form"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel
                        :link="route('admin.lockers.index')"
                        class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-forms.actions>
            </div>
        </x-form>
    </div>
</div>

<script src="{{ asset('vendor/jquery/dropzone/dropzone.min.js') }}"></script>
<script>

    if ('{{ $locker->status }}' != '{{ \App\Enums\LockerStatus::AVAILABLE }}') {
        $('#status').val('{{ \App\Enums\LockerStatus::IN_USE }}');
        $('#status').trigger('change');
    }

    $(document).ready(function() {
        datepicker(
            '#date_of_manufacture',{
                position: 'bl',
                ...datepickerConfig
            }
        )
        UTELocker.common.init(RIGHT_MODAL);
    });

    $('#status').on('change', function() {
        if ($(this).val() == '{{ \App\Enums\LockerStatus::IN_USE }}') {
            $('#cancel_reason').val('');
            $('#row_cancel_reason').hide();
        } else {
            Swal.fire({
                title: 'Are you sure to change status?',
                text: 'If you change status to Brken or Under Maintenance, all bookings will be canceled.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('app.confirm') }}',
                cancelButtonText: '{{ __('app.cancel') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#row_cancel_reason').show();
                } else {
                    $('#status').val('{{ \App\Enums\LockerStatus::IN_USE }}');
                    $('#status').trigger('change');
                }
            });
        }
    });

    function checkboxChange(parentClass, id) {
        let checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

    $('#save-locker-form').click(function() {
        const url = "{{ route('admin.lockers.update', $locker->id) }}";
        const data = $('#save-locker-data-form').serialize();

        saveLocker(data, url, '#save-locker-form');
    });

    function saveLocker(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#save-locker-data-form',
            type: "POST",
            disableButton: true,
            blockUI: true,
            file: true,
            buttonSelector: "#save-form",
            data: $('#save-data-form').serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    window.location.href = response.redirectUrl;
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        })
    }
</script>
