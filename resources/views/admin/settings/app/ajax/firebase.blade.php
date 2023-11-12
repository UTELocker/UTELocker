<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_api_key"
                :fieldLabel="__('modules.settings.firebase_api_key')"
                fieldName="firebase_api_key"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_api_key ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_auth_domain"
                :fieldLabel="__('modules.settings.firebase_auth_domain')"
                fieldName="firebase_auth_domain"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_auth_domain ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_project_id"
                :fieldLabel="__('modules.settings.firebase_project_id')"
                fieldName="firebase_project_id"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_project_id ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_storage_bucket"
                :fieldLabel="__('modules.settings.firebase_storage_bucket')"
                fieldName="firebase_storage_bucket"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_storage_bucket ?? '' }}">
            </x-forms.text>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_messaging_sender_id"
                :fieldLabel="__('modules.settings.firebase_messaging_sender_id')"
                fieldName="firebase_messaging_sender_id"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_messaging_sender_id ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_app_id"
                :fieldLabel="__('modules.settings.firebase_app_id')"
                fieldName="firebase_app_id"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_app_id ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="firebase_measurement_id"
                :fieldLabel="__('modules.settings.firebase_measurement_id')"
                fieldName="firebase_measurement_id"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->firebase_measurement_id ?? '' }}">
            </x-forms.text>
        </div>
    </div>
</div>

<div class="w-100 border-top-grey set-btns">
    <x-settings.form-actions>
        <x-forms.button-primary id="save-app-settings-form" class="mr-3" icon="check">
            {{ __('app.save') }}
        </x-forms.button-primary>
    </x-settings.form-actions>
</div>
