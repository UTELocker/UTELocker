<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_mailer"
                :fieldLabel="__('modules.settings.email_mailer')"
                fieldName="email_mailer"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_mailer ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_host"
                :fieldLabel="__('modules.settings.email_host')"
                fieldName="email_host"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_host ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_port"
                :fieldLabel="__('modules.settings.email_port')"
                fieldName="email_port"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_port ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_username"
                :fieldLabel="__('modules.settings.email_username')"
                fieldName="email_username"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_username ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <label for="email_password">{{ __('modules.settings.email_password') }}</label>
            <x-forms.input-group>
                <input type="password" name="email_password" id="email_password"
                        placeholder="{{ __('modules.settings.email_password') }}" tabindex="3"
                        class="form-control height-50 f-15 light_text @error('email_password') is-invalid @enderror">

                <x-slot name="append">
                    <button type="button" data-toggle="tooltip"
                            data-original-title="{{ __('app.viewPassword') }}"
                            class="btn btn-outline-secondary border-grey height-50 toggle-password">
                        <i
                            class="fa fa-eye"></i></button>
                </x-slot>

            </x-forms.input-group>
            @if ($errors->has('email_password'))
                <div class="invalid-feedback d-block">{{ $errors->first('email_password') }}</div>
            @endif
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_encryption"
                :fieldLabel="__('modules.settings.email_encryption')"
                fieldName="email_encryption"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_encryption ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="email_from_address"
                :fieldLabel="__('modules.settings.email_from_address')"
                fieldName="email_from_address"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->email_from_address ?? '' }}">
            </x-forms.text>
        </div>
    </div>
</div>

<div class="w-100 border-top-grey set-btns">
    <x-settings.form-actions>
        <x-forms.button-primary id="save-app-settings-site-group-form" class="mr-3" icon="check">
            {{ __('app.save') }}
        </x-forms.button-primary>
    </x-settings.form-actions>
</div>
