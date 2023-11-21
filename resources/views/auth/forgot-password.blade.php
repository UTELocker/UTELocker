<x-auth>
    <form id="forgot-password-form" action="{{ route('password.email') }}" class="ajax-form" method="POST">
        @csrf
        <h3 class="text-capitalize mb-4 f-w-500">{{ __('app.recoverPassword') }}</h3>

        <div class="alert alert-success m-t-10 d-none" id="success-msg"></div>
        <div class="group">
            <div class="form-group text-left">
                <label for="email" class="f-w-500">{{ __('auth.email') }}</label>
                <input type="email" name="email" class="form-control height-50 f-15 light_text"
                       autofocus placeholder="{{ __('placeholders.email') }}" id="email">
            </div>

            <button type="button" id="submit-email"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18  @if(isset($cliets)) d-none @endif">
                {{ __('app.getSiteGroup') }} <i class="fa fa-arrow-right pl-1"></i>
            </button>

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

                <button
                    type="button"
                    id="submit-login"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18">
                    @lang('app.sendPasswordLink') <i class="fa fa-arrow-right pl-1"></i>
                </button>
            </div>
        </div>
        <div class="forgot_pswd mt-3">
            <a href="{{ route('login') }}" class="justify-content-center">{{ __('app.login') }}</a>
        </div>
    </form>
    <x-slot name="scripts">
        <script>
            $('#email').focus();

            $('#email').on('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $('#submit-email').trigger('click');
                    }
                });

            $("#submit-email").on('click', function () {
                const email = $('#email').val();
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{__('app.loading')}}';

                $('#email').removeClass('is-invalid');
                $('#email').parent().find('.invalid-feedback').remove();

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

            if ($('#email').val() !== '') {
                $('#submit-email').trigger('click');
            }

            $('#submit-login').click(function () {
                const url = "{{ route('password.email') }}";
                $.easyAjax({
                    url: url,
                    container: '#forgot-password-form',
                    disableButton: true,
                    blockUI: true,
                    buttonSelector: "#submit-login",
                    type: "POST",
                    data: $('#forgot-password-form').serialize(),
                    success: function (response) {
                        $('#success-msg').removeClass('d-none');
                        $('#success-msg').html(response.message);
                        $('.group').remove();
                    }
                })
            });
        </script>
    </x-slot>
</x-auth>
