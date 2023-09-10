<div class="row">
    <div class="col-sm-12">
        <x-form id="save-user-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    User Details
                </h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-4">
                                <x-forms.text
                                    fieldId="user_name"
                                    :fieldLabel="__('modules.users.name')"
                                    fieldName="user_name"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('placeholders.name')"
                                    :fieldValue="$user->name ?? ''"></x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.email
                                    fieldId="user_email"
                                    :fieldLabel="__('app.email')"
                                    fieldName="user_email"
                                    :popover="__('modules.clients.emailNote')"
                                    :fieldPlaceholder="__('placeholders.email')"
                                    :fieldValue="$user->email ?? ''">
                                </x-forms.email>
                            </div>
                            <div class="col-md-4">
                                <x-forms.text
                                    fieldId="user_mobile"
                                    :fieldLabel="__('app.mobile')"
                                    fieldName="user_mobile"
                                    :fieldPlaceholder="__('placeholders.mobile')"
                                    :fieldValue="$user->mobile ?? ''">
                                </x-forms.text>
                            </div>
                            <div class="col-md-4">
                                <x-forms.label
                                    class="mt-3"
                                    fieldId="user_password"
                                    :fieldLabel="__('app.password')"
                                    :popover="__('messages.requiredForLogin')">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <input
                                        type="password"
                                        name="user_password"
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
                                <x-forms.select fieldId="user_gender" :fieldLabel="__('modules.profile.gender')"
                                                fieldName="user_gender">
                                    @foreach ($user_gender as $key => $value)
                                        <option @if ($key == $user->gender) selected @endif value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            @if ($user->type != \App\Enums\UserRole::SUPER_USER)
                                <div class="col-md-4">
                                    <x-forms.select fieldId="user_type" :fieldLabel="__('modules.users.usertype')"
                                                    fieldName="user_type">
                                        @foreach ($user_role as $key => $value)
                                            <option @if ($key == $user->type) selected @endif value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </x-forms.select>
                                </div>
                            @endif
                            <div class="col-md-4">
                                @include('components.select-languages', ['name' => 'user_locale'])
                            </div>
                            @if ($user->type != \App\Enums\UserRole::SUPER_USER)
                                <div class="col-md-4">
                                    <x-forms.select fieldId="user_client_id" :fieldLabel="__('modules.users.client')"
                                                    fieldName="user_client_id" :disabled="(user()->type != \App\Enums\UserRole::SUPER_USER)" search="true">
                                        @foreach($clients as $client)
                                            <option @if ($user->client_id == $client->id) selected @endif value={{ $client->id }}}>{{ $client->name }}</option>
                                        @endforeach
                                    </x-forms.select>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <x-forms.file
                            allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.profile.profilePicture')"
                            fieldName="user_avatar"
                            fieldId="user_avatar"
                            :fieldValue="$user_avatar ?? ''"
                            fieldHeight="119"
                            :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                    <input type ="hidden" name="add_more" value="false" id="add_more" />
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-user-form"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-secondary
                        class="mr-3"
                        id="save-more-user-form"
                        icon="check-double">@lang('app.saveAddMore')
                    </x-forms.button-secondary>
                    <x-forms.button-cancel
                        :link="route('admin.users.index')"
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

    $('#save-user-form').click(function() {
        const url = "{{ route('admin.users.update', $user->id) }}";
        const data = $('#save-user-data-form').serialize();

        saveUser(data, url, '#save-user-form');
    });

    function saveUser(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#save-user-data-form',
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
