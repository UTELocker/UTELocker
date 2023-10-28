<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-3">
            <x-forms.text
                fieldId="pusher_app_id"
                :fieldLabel="__('modules.settings.pusher_app_id')"
                fieldName="pusher_app_id"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->pusher_app_id ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="pusher_app_id"
                :fieldLabel="__('modules.settings.pusher_app_key')"
                fieldName="pusher_app_key"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->pusher_app_key ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="pusher_app_id"
                :fieldLabel="__('modules.settings.pusher_app_secret')"
                fieldName="pusher_app_secret"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->pusher_app_secret ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-3">
            <x-forms.text
                fieldId="pusher_app_id"
                :fieldLabel="__('modules.settings.pusher_app_cluster')"
                fieldName="pusher_app_cluster"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ globalSettings()->pusher_app_cluster ?? '' }}">
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
