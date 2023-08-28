<div class="row">
    <div class="col-sm-12">
        <x-form id="save-client-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.clients.companyDetails')
                </h4>
                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="client_name"
                            :fieldLabel="__('modules.clients.companyName')"
                            fieldName="client_name"
                            :fieldPlaceholder="__('placeholders.company')"
                            :fieldValue="$client->name ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.email
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="client_email"
                            :fieldLabel="__('modules.clients.companyEmail')"
                            fieldName="client_email"
                            :fieldPlaceholder="__('placeholders.companyEmail')"
                            :fieldValue="$client->email ?? ''">
                        </x-forms.email>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="client_website"
                            :fieldLabel="__('modules.clients.website')"
                            fieldName="client_website"
                            fieldPlaceholder="e.g. https://hcmute.edu.vn"
                            :fieldValue="$client->website ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="client_phone"
                            :fieldLabel="__('modules.clients.officePhoneNumber')"
                            fieldName="client_phone"
                            :fieldPlaceholder="__('placeholders.mobile')"
                            :fieldValue="$client->phone ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group my-3">
                            <x-forms.textarea
                                :fieldLabel="__('modules.accountSettings.companyAddress')"
                                fieldName="client_address"
                                fieldId="client_address"
                                :fieldPlaceholder="__('placeholders.address')"
                                :fieldValue="$client->address ?? ''">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <x-forms.file
                            allowedFileExtensions="png jpg jpeg svg"
                            class="mr-0 mr-lg-2 mr-md-2"
                            :fieldLabel="__('modules.clients.companyLogo')"
                            fieldName="client_logo"
                            :fieldValue="$client_logo ?? ''"
                            fieldId="client_logo"
                            :popover="__('messages.fileFormat.ImageFile')"/>
                    </div>
                    <input type ="hidden" name="add_more" value="false" id="add_more" />
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-client-form"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-secondary
                        class="mr-3"
                        id="save-more-client-form"
                        icon="check-double">@lang('app.saveAddMore')
                    </x-forms.button-secondary>
                    <x-forms.button-cancel
                        :link="route('admin.clients.index')"
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

        $('#random_password').click(function() {
            const randPassword = Math.random().toString(36).substr(2, 8);

            $('#password').val(randPassword);
        });

        UTELocker.common.init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        let checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

    $('#save-client-form').click(function() {
        const url = "{{ route('admin.clients.update', $client->id) }}";
        const data = $('#save-client-data-form').serialize();

        saveClient(data, url, '#save-client-form');
    });

    function saveClient(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#save-client-data-form',
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
