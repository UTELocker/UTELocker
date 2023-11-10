<x-auth>
    <form id="register-form"  method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group text-left" id="client-section">
            <label for="client_id">{{ __('app.listSiteGroups') }}</label>
            <select
                class="form-control height-50 f-15 light_text @error('client_id') is-invalid @enderror"
                name="client_id" id="client" tabindex="2">
                    <option value="">{{ __('app.listSiteGroups') }}</option>
            </select>
            @if ($errors->has('client_id'))
                <div class="invalid-feedback pt-1">{{ $errors->first('client_id') }}</div>
            @endif
        </div>

        <div class="form-group text-left">
            <label for="name">{{ __('auth.name') }}</label>
            <input tabindex="1" type="text" name="name"
                   class="form-control height-50 f-15 light_text @error('name') is-invalid @enderror"
                   autofocus
                   value="{{request()->old('name')}}"
                   placeholder="@lang('auth.name')" id="name">
            @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="form-group text-left">
            <label for="email">{{ __('auth.email') }}</label>
            <input tabindex="1" type="email" name="email"
                   class="form-control height-50 f-15 light_text @error('email') is-invalid @enderror"
                   autofocus
                   value="{{request()->old('email')}}"
                   placeholder="@lang('auth.email')" id="email">
            @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="form-group text-left">
            <label for="mobile">{{ __('auth.phone') }}</label>
            <input tabindex="1" type="number" name="mobile"
                   class="form-control height-50 f-15 light_text @error('mobile') is-invalid @enderror"
                   autofocus
                   value="{{request()->old('mobile')}}"
                   placeholder="@lang('auth.phone')" id="mobile">
            @if ($errors->has('mobile'))
                <div class="invalid-feedback">{{ $errors->first('mobile') }}</div>
            @endif
        </div>

        <div class="form-group text-left" id="client-section">
            <label for="gender">{{ __('auth.gender') }}</label>
            <select
                class="form-control height-50 f-15 light_text @error('gender') is-invalid @enderror"
                name="gender" id="client" tabindex="2"
            >
                <option value="" disabled selected>Select your gender</option>
                <option value="{{\App\Enums\UserGender::MALE}}" @selected(old('gender') == \App\Enums\UserGender::MALE)>
                    {{ \App\Enums\UserGender::getDescriptions()[\App\Enums\UserGender::MALE] }}
                </option>
                <option value="{{\App\Enums\UserGender::FEMALE}}" @selected(old('gender') == \App\Enums\UserGender::FEMALE)>
                    {{ \App\Enums\UserGender::getDescriptions()[\App\Enums\UserGender::FEMALE] }}
                </option>
                <option value="{{\App\Enums\UserGender::OTHER}}" @selected(old('gender') == \App\Enums\UserGender::OTHER)>
                    {{ \App\Enums\UserGender::getDescriptions()[\App\Enums\UserGender::OTHER] }}
                </option>
            </select>
            @if ($errors->has('gender'))
                <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
            @endif
        </div>

        <div class="form-group text-left">
            <label for="password">{{ __('app.password') }}</label>
            <x-forms.input-group>
                <input type="password" name="password" id="password"
                       placeholder="{{ __('placeholders.password') }}" tabindex="3"
                       class="form-control height-50 f-15 light_text @error('password') is-invalid @enderror">

                <x-slot name="append">
                    <button type="button" data-toggle="tooltip"
                            data-original-title="{{ __('app.viewPassword') }}"
                            class="btn btn-outline-secondary border-grey height-50 toggle-password">
                        <i
                            class="fa fa-eye"></i></button>
                </x-slot>

            </x-forms.input-group>
            @if ($errors->has('password'))
                <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="form-group text-left">
            <label for="password_confirmation">{{ __('auth.confirm_password') }}</label>
            <x-forms.input-group>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="{{ __('placeholders.password') }}" tabindex="3"
                       class="form-control height-50 f-15 light_text @error('password_confirmation') is-invalid @enderror">

                <x-slot name="append">
                    <button type="button" data-toggle="tooltip"
                            data-original-title="{{ __('app.viewPassword') }}"
                            class="btn btn-outline-secondary border-grey height-50 toggle-password">
                        <i
                            class="fa fa-eye"></i></button>
                </x-slot>

            </x-forms.input-group>
            @if ($errors->has('password_confirmation'))
                <div class="invalid-feedback d-block">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" id="submit-register"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18 ">
                {{ __('app.register') }} <i class="fa fa-arrow-right pl-1"></i>
            </button>
        </div>
    </form>
    <x-slot name="scripts">
        <script>
            $(document).ready(function () {
                const data = {
                    _token: '{{ csrf_token() }}',
                }
                const urlParams = new URLSearchParams(window.location.search);
                const token = urlParams.get('token');
                if (token) {
                    data.token = token;
                }
                $.ajax({
                    url: '{{ route('api.user.listClientGuest') }}',
                    type: 'GET',
                    data: data,
                    success: function (data) {
                        if (data.status == 'success') {
                            const placeholder = "{{ __('app.listSiteGroups') }}";
                            $('#client').html('');
                            let optionPlaceholder = `<option value=""`;
                            if (token) {
                                optionPlaceholder += ` disabled`;
                            }
                            optionPlaceholder += `>${placeholder}</option>`;
                            $('#client').append(optionPlaceholder);
                            $.each(data.data, function (key, value) {
                                $('#client').append(`<option value="${value.id}">${value.name}</option>`);
                            });
                        } else {
                            Swal.fire({
                                title: '{{ __('app.error') }}',
                                text: data.message,
                                icon: 'error',
                            }).then((result) =>{
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route('login') }}';
                                }
                            });
                        }
                    },
                    error: function (data) {
                        Swal.fire({
                            title: '{{ __('app.error') }}',
                            text: '{{ __('app.something_went_wrong') }}',
                            icon: 'error',
                        }).then((result) =>{
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('login') }}';
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-auth>
