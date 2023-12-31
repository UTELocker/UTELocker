<div class="col-xl-12 col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4">
    <div class="row">
        <div class="col-lg-12">
            <x-forms.file
                allowedFileExtensions="png jpg jpeg svg" class="mr-0 mr-lg-2 mr-md-2 cropper"
                :fieldLabel="__('modules.profile.profilePicture')"
                fieldName="user_avatar"
                fieldId="user_avatar"
                :fieldValue="$user_avatar ?? ''"
                fieldHeight="119"
                :popover="__('messages.fileFormat.ImageFile')"
            />
        </div>
        <div class="col-lg-4">
            <x-forms.text
                fieldId="user_name"
                :fieldLabel="__('modules.users.name')"
                fieldName="user_name"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                :fieldValue="$user->name ?? ''"
            >
            </x-forms.text>
        </div>
        <div class="col-lg-4">
            <x-forms.email
                fieldId="user_email"
                :fieldLabel="__('app.email')"
                fieldName="user_email"
                :popover="__('modules.clients.emailNote')"
                :fieldPlaceholder="__('placeholders.email')"
                :fieldValue="$user->email ?? ''">
            </x-forms.email>
        </div>
        <div class="col-lg-4">
            <x-forms.text
                fieldId="user_mobile"
                :fieldLabel="__('app.mobile')"
                fieldName="user_mobile"
                :fieldPlaceholder="__('placeholders.mobile')"
                :fieldValue="$user->mobile ?? ''">
            </x-forms.text>
        </div>
        <div class="col-lg-4">
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
        <div class="col-lg-4">
            <x-forms.select fieldId="user_gender" :fieldLabel="__('modules.profile.gender')"
                            fieldName="user_gender">
                @foreach ($user_gender as $key => $value)
                    <option @if ($key == $user->gender) selected @endif value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-forms.select>
        </div>
        @if ($user->type != \App\Enums\UserRole::SUPER_USER)
            <div class="col-lg-4">
                <x-forms.select fieldId="user_type" :fieldLabel="__('modules.users.usertype')"
                                fieldName="user_type">
                    @foreach ($user_role as $key => $value)
                        <option @if ($key == $user->type) selected @endif value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-forms.select>
            </div>
        @endif
        <div class="col-lg-4">
            @include('components.select-languages', ['name' => 'user_locale'])
        </div>
        @if ($user->type != \App\Enums\UserRole::SUPER_USER)
            <div class="col-lg-4">
                <x-forms.select fieldId="user_client_id" :fieldLabel="__('modules.users.client')"
                                fieldName="user_client_id" :disabled="(user()->type != \App\Enums\UserRole::SUPER_USER)" search="true">
                    @foreach($clients as $client)
                        <option @if ($user->client_id == $client->id) selected @endif value={{ $client->id }}>{{ $client->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>
        @endif
        <div class="col-lg-4">
            <x-forms.toggle-switch fieldId="user_is2FA" :fieldLabel="__('modules.settings.is2FA')"
                fieldName="user_is2FA" :checked="$user->is2FA == true"
                :popover="__('modules.settings.is2FANote')">
            </x-forms.toggle-switch>
        </div>
    </div>
</div>

<div class="w-100 border-top-grey set-btns">
    <x-settings.form-actions>
        <x-forms.button-primary id="save-profile-form" class="mr-3" icon="check">
            {{ __('app.save') }}
        </x-forms.button-primary>
    </x-settings.form-actions>
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

    $('#save-profile-form').click(function() {
        const url = "{{ route('admin.users.update', $user->id) }}";
        const data = $('#editSettings').serialize();

        saveProfile(data, url, '#save-profile-form');
    });

    function saveProfile(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#editSettings',
            type: "POST",
            disableButton: true,
            blockUI: true,
            file: true,
            buttonSelector: buttonSelector,
            data: data,
            success: function(response) {

            }
        })
    }
</script>
