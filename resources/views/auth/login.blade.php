<x-auth>
    <form id="login-form" action="{{ route('login') }}" class="ajax-form" method="POST">
        @csrf
        <h3 class="text-capitalize mb-4 f-w-500">{{ __('app.login') }}</h3>

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

        <button type="button" id="submit-email"
                class="btn-primary f-w-500 rounded w-100 height-50 f-18  @if(isset($cliets)) d-none @endif">
            {{ __('app.getSiteGroup') }} <i class="fa fa-arrow-right pl-1"></i>
        </button>

        <a href="{{ route('register') }}" id="btnRegister">
            <button type="button" id="submit-email"
                    class="btn-secondary f-w-500 rounded w-100 height-50 f-18">
                {{ __('app.register') }} <i class="fa fa-arrow-right pl-1"></i>
            </button>
        </a>

        <div class="@if(!isset($cliets)) d-none @endif" id="site-group-section">
            <div class="form-group text-left" id="client-section">
                <label for="client_id">{{ __('app.listSiteGroups') }}</label>
                <select
                    class="form-control height-50 f-15 light_text @error('client') is-invalid @enderror"
                    name="client_id" id="client" tabindex="2">
                    <option value="">{{ __('app.listSiteGroups') }}</option>
                </select>
                @if ($errors->has('username'))
                    <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                @endif
            </div>

            <div id="password-section">
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
                <div class="forgot_pswd mb-3">
                    <a href="{{ url('forgot-password') }}">{{ __('app.forgotPassword') }}</a>
                </div>

                <div class="form-group text-left ">
                    <input id="checkbox-signup" class="cursor-pointer" type="checkbox" name="remember">
                    <label for="checkbox-signup" class="cursor-pointer">{{ __('app.rememberMe') }}</label>
                </div>

                <button type="submit" id="submit-login"
                        class="btn-primary f-w-500 rounded w-100 height-50 f-18">
                    {{ __('app.login') }} <i class="fa fa-arrow-right pl-1"></i>
                </button>
            </div>
        </div>
    </form>
    <x-slot name="scripts">
        <script>
            $(document).ready(function () {
                $("form#login-form").submit(function () {
                    const button = $('form#login-form').find('#submit-login');
                    const text =
                        '<span class="spinner-border spinner-border-sm"'
                        + ' role="status" aria-hidden="true"></span> {{__('app.loading')}}';
                    button.prop("disabled", true);
                    button.html(text);
                });

                $('#email').on('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $('#submit-email').trigger('click');
                    }
                });

                $("#submit-email").on('click', function () {
                    const email = $('#email').val();
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{__('app.loading')}}';

                    $.ajax({
                        url: '{{ route('api.user.listClient') }}',
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                            email: email
                        },
                        success: function (data) {
                            $('#btnRegister').addClass('d-none');
                            $('#email').attr('readonly', true);
                            $('#submit-email').addClass('d-none');
                            $('#site-group-section').removeClass('d-none');
                            if (data.role === {{\App\Enums\UserRole::SUPER_USER}}) {
                                $('#client-section').addClass('d-none');
                            } else {
                                const placeholder = "{{ __('app.listSiteGroups') }}";
                                $('#client').html('');
                                $('#client').append(`<option value="">${placeholder}</option>`);
                                $.each(data.data, function (key, value) {
                                    $('#client').append(`<option value="${value.id}">${value.name}</option>`);
                                });
                            }
                        },
                        error: function (data) {
                            const html = `<div class="invalid-feedback">${
                                data.responseJSON.message
                            }</div>`;
                            $('#email').addClass('is-invalid');
                            $('#email').parent().append(html);
                            $('#submit-email').html('{{ __('app.getSiteGroup') }} <i class="fa fa-arrow-right pl-1"></i>');
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-auth>
