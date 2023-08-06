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

        <div id="password-section">
            <div class="form-group text-left">
                <label for="password">@lang('app.password')</label>
                <x-forms.input-group>
                    <input type="password" name="password" id="password"
                           placeholder="@lang('placeholders.password')" tabindex="3"
                           class="form-control height-50 f-15 light_text @error('password') is-invalid @enderror">

                    <x-slot name="append">
                        <button type="button" data-toggle="tooltip"
                                data-original-title="@lang('app.viewPassword')"
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
                <a href="{{ url('forgot-password') }}">@lang('app.forgotPassword')</a>
            </div>

            <div class="form-group text-left ">
                <input id="checkbox-signup" class="cursor-pointer" type="checkbox" name="remember">
                <label for="checkbox-signup" class="cursor-pointer">@lang('app.rememberMe')</label>
            </div>

            <button type="submit" id="submit-login"
                    class="btn-primary f-w-500 rounded w-100 height-50 f-18">
                @lang('app.login') <i class="fa fa-arrow-right pl-1"></i>
            </button>
        </div>
    </form>
</x-auth>
