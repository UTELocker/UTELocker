<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-12">
            <x-forms.text
                fieldId="refund_soon_cancel_booking"
                :fieldLabel="__('modules.settings.refund_soon_cancel_booking')"
                fieldName="refund_soon_cancel_booking"
                fieldRequired="true"
                :fieldPlaceholder="__('placeholders.name')"
                fieldValue="{{ siteGroup()->refund_soon_cancel_booking ?? '' }}">
            </x-forms.text>
        </div>
        <div class="col-lg-12">
            <div id="description"></div>
            <textarea name="config_policy" id="description-text" class="d-none"></textarea>
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
