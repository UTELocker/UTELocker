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
                                <x-forms.text
                                    fieldId="warranty_duration"
                                    :fieldLabel="__('modules.lockers.warrantyDuration')"
                                    fieldName="warranty_duration"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('modules.lockers.placeholders.warrantyDuration')"
                                    :fieldValue="$locker->warranty_duration ?? ''">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="status" :fieldLabel="__('modules.lockers.status')"
                                                fieldName="status"
                                                fieldRequired="true"
                                >
                                    @foreach(
                                        \App\Enums\LockerStatus::getDescriptions([\App\Enums\LockerStatus::IN_USE])
                                        as $key => $status
                                    )
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>
                        <div class="row">
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
                    </div>
                    <div class="col-lg-4">
                        <x-forms.file
                            allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.lockers.image')"
                            fieldName="image"
                            fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
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
    $(document).ready(function() {
        datepicker(
            '#date_of_manufacture',{
                position: 'bl',
                ...datepickerConfig
            }
        )
        UTELocker.common.init(RIGHT_MODAL);
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
                }
            }
        })
    }
</script>
