<div class="row">
    <div class="col-sm-12">
        <x-form id="save-user-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Account Details
                </h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    fieldId="name"
                                    :fieldLabel="__('modules.clients.clientName')"
                                    fieldName="name"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.name')"
                                    :fieldValue="$user->name ?? ''"></x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.email
                                    fieldId="email"
                                    :fieldLabel="__('app.email')"
                                    fieldName="email"
                                    :popover="__('modules.clients.emailNote')"
                                    :fieldPlaceholder="__('placeholders.email')"
                                    :fieldValue="$user->email ?? ''">
                                </x-forms.email>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    fieldId="mobile"
                                    :fieldLabel="__('app.mobile')"
                                    fieldName="mobile"
                                    :fieldPlaceholder="__('placeholders.mobile')"
                                    :fieldValue="$user->mobile ?? ''">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label
                                    class="mt-3"
                                    fieldId="password"
                                    :fieldLabel="__('app.password')"
                                    :popover="__('messages.requiredForLogin')">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control height-35 f-14">
                                    <x-slot name="preappend">
                                        <button type="button" data-toggle="tooltip"
                                                data-original-title="@lang('app.viewPassword')"
                                                class="btn btn-outline-secondary border-grey height-35 toggle-password">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="append">
                                        <button id="random_password" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('modules.clients.generateRandomPassword')"
                                                class="btn btn-outline-secondary border-grey height-35">
                                            <i class="fa fa-random"></i>
                                        </button>
                                    </x-slot>
                                </x-forms.input-group>
                                <small class="form-text text-muted">@lang('placeholders.password')</small>
                            </div>
                            <div class="col-md-4">
                                <x-forms.select fieldId="gender" :fieldLabel="__('modules.profile.gender')"
                                                fieldName="gender">
                                    <option value="male">@lang('app.male')</option>
                                    <option value="female">@lang('app.female')</option>
                                    <option value="others">@lang('app.others')</option>
                                </x-forms.select>
                            </div>
                            <div class="col-md-4">
                                @include('components.select-languages')
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <x-forms.file
                            allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.profile.profilePicture')" fieldName="image" fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                </div>

                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.clients.companyDetails')
                </h4>
                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="company_name"
                            :fieldLabel="__('modules.clients.companyName')"
                            fieldName="company_name"
                            :fieldPlaceholder="__('placeholders.company')"
                            :fieldValue="$client->name ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.email
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="emailCompany"
                            :fieldLabel="__('modules.clients.companyEmail')"
                            fieldName="email"
                            :fieldPlaceholder="__('placeholders.companyEmail')"
                            :fieldValue="$client->email ?? ''">
                        </x-forms.email>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="website"
                            :fieldLabel="__('modules.clients.website')"
                            fieldName="website"
                            fieldPlaceholder="e.g. https://hcmute.edu.vn"
                            :fieldValue="$client->website ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text
                            class="mb-3 mt-3 mt-lg-0 mt-md-0"
                            fieldId="office"
                            :fieldLabel="__('modules.clients.officePhoneNumber')"
                            fieldName="office"
                            :fieldPlaceholder="__('placeholders.mobile')"
                            :fieldValue="$client->phone ?? ''">
                        </x-forms.text>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group my-3">
                            <x-forms.textarea
                                :fieldLabel="__('modules.accountSettings.companyAddress')"
                                fieldName="address"
                                fieldId="address"
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
                            fieldName="company_logo"
                            :fieldValue="(siteGroup()->logo) ?? ''"
                            fieldId="company_logo"
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
                    {{--                        <x-forms.button-cancel --}}
                    {{--                            :link="route('clients.index')" --}}
                    {{--                            class="border-0">@lang('app.cancel')--}}
                    {{--                        </x-forms.button-cancel>--}}
                </x-forms.actions>
            </div>
        </x-form>
    </div>
</div>

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
</script>
