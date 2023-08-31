<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
    <div class="row">
        <div class="col-lg-3">
            <x-forms.select
                fieldId="date_format"
                :fieldLabel="__('modules.settings.dateFormat')"
                fieldName="date_format"
                search="true">
                @foreach ($dateFormat as $format)
                    <option value="{{ $format }}"
                            @if (siteGroupOrGlobalSetting()->date_format == $format) selected @endif>
                        {{ $format }} ({{ $dateObject->translatedFormat($format) }})
                    </option>
                @endforeach
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="time_format" :fieldLabel="__('modules.settings.timeFormat')"
                            fieldName="time_format" search="true">
                <option value="h:i A" @if (siteGroupOrGlobalSetting()->time_format == 'h:i A') selected @endif>
                    12 @lang('app.hour') ({{ now(siteGroupOrGlobalSetting()->timezone)->translatedFormat('h:i A') }})
                </option>
                <option value="h:i a" @if (siteGroupOrGlobalSetting()->time_format == 'h:i a') selected @endif>
                    12 @lang('app.hour') ({{ now(siteGroupOrGlobalSetting()->timezone)->translatedFormat('h:i a') }})
                </option>
                <option value="H:i" @if (siteGroupOrGlobalSetting()->time_format == 'H:i') selected @endif>
                    24 @lang('app.hour') ({{ now(siteGroupOrGlobalSetting()->timezone)->translatedFormat('H:i') }})
                </option>
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="timezone" :fieldLabel="__('modules.settings.defaultTimezone')"
                            fieldName="timezone" search="true">
                @foreach ($timezones as $tz)
                    <option @if (siteGroupOrGlobalSetting()->timezone == $tz) selected @endif
                        value="{{ $tz }}">{{ $tz }}
                    </option>
                @endforeach
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="locale" :fieldLabel="__('modules.accountSettings.language')"
                            fieldName="locale" search="true" :popover="__('modules.accountSettings.appLanguageInfo')">
                @foreach ($languageSettings as $language)
                    <option
                        {{ siteGroupOrGlobalSetting()->locale == $language->language_code ? 'selected' : '' }}
                    data-content="<span class='flag-icon flag-icon-{{ ($language->flag_code == 'en')
                    ? 'gb'
                    : strtolower($language->flag_code) }} flag-icon-squared'></span> {{ $language->language_name }}"
                        value="{{ $language->language_code }}">{{ $language->language_name }}</option>
                @endforeach
            </x-forms.select>
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
