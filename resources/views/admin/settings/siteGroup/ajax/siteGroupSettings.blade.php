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
                            @if (siteGroup()->date_format == $format) selected @endif>
                        {{ $format }} ({{ $dateObject->translatedFormat($format) }})
                    </option>
                @endforeach
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="time_format" :fieldLabel="__('modules.settings.timeFormat')"
                            fieldName="time_format" search="true">
                <option value="h:i A" @if (siteGroup()->time_format == 'h:i A') selected @endif>
                    12 @lang('app.hour') ({{ now(siteGroup()->timezone)->translatedFormat('h:i A') }})
                </option>
                <option value="h:i a" @if (siteGroup()->time_format == 'h:i a') selected @endif>
                    12 @lang('app.hour') ({{ now(siteGroup()->timezone)->translatedFormat('h:i a') }})
                </option>
                <option value="H:i" @if (siteGroup()->time_format == 'H:i') selected @endif>
                    24 @lang('app.hour') ({{ now(siteGroup()->timezone)->translatedFormat('H:i') }})
                </option>
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="timezone" :fieldLabel="__('modules.settings.defaultTimezone')"
                            fieldName="timezone" search="true">
                @foreach ($timezones as $tz)
                    <option @if (siteGroup()->timezone == $tz) selected @endif
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
                        {{ siteGroup()->locale == $language->language_code ? 'selected' : '' }}
                        data-content="<span class='flag-icon flag-icon-{{ ($language->flag_code == 'en')
                    ? 'gb'
                    : strtolower($language->flag_code) }} flag-icon-squared'></span> {{ $language->language_name }}"
                        value="{{ $language->language_code }}">{{ $language->language_name }}</option>
                @endforeach
            </x-forms.select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <x-forms.select fieldId="status" :fieldLabel="__('modules.settings.statusSiteGroup')"
                            fieldName="status">
                <option
                    {{ siteGroup()->status == \App\Enums\ClientStatus::PUBLIC ? 'selected' : '' }}
                    value="{{ \App\Enums\ClientStatus::PUBLIC }}"> @lang('modules.settings.public')
                </option>
                <option
                    {{ siteGroup()->status == \App\Enums\ClientStatus::PRIVATE ? 'selected' : '' }}
                    value="{{ \App\Enums\ClientStatus::PRIVATE }}"> @lang('modules.settings.private')
            </x-forms.select>
        </div>
        <div class="col-lg-3">
            <x-forms.select fieldId="allow_signup" :fieldLabel="__('modules.settings.allowGuestRegistration')"
                            fieldName="allow_signup">
                <option
                    {{ siteGroup()->allow_signup == \App\Classes\CommonConstant::DATABASE_YES ? 'selected' : '' }}
                    value="{{ \App\Classes\CommonConstant::DATABASE_YES }}"> @lang('modules.settings.allowSignup')
                </option>

                <option
                    {{ siteGroup()->allow_signup == \App\Classes\CommonConstant::DATABASE_NO ? 'selected' : '' }}
                    value="{{ \App\Classes\CommonConstant::DATABASE_NO }}"> @lang('modules.settings.notAllowSignup')
                </option>
            </x-forms.select>
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
