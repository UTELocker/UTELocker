<x-forms.select
    fieldId="{{ $name }}"
    :fieldLabel="__('modules.accountSettings.changeLanguage')"
    fieldName="{{ $name }}" search="true">
    @foreach ($languages as $language)
        <option
            {{ \Auth::user()->locale == $language->language_code ? 'selected' : '' }}
            data-content="<span class='flag-icon flag-icon-{{ ($language->flag_code == 'en')
            ? 'gb'
            : strtolower($language->flag_code) }} flag-icon-squared'></span> {{ $language->language_name }}"
            value="{{ $language->language_code }}">{{ $language->language_name }}
        </option>
    @endforeach
</x-forms.select>
