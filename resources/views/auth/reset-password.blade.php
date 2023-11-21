<x-auth>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <p>
            Hi {{ $user->name }}, <br>
            Please enter your new password of your account in <br> {{ $user->client_name }}.
        </p>

        <!-- Password -->
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

        <!-- Confirm Password -->

        <div class="form-group text-left">
            <label for="password_confirmation">{{ __('app.password') }}</label>
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
            @if ($errors->has('password'))
                <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <button
            type="submit"
            id="submit-login"
            class="btn-primary f-w-500 rounded w-100 height-50 f-18">
            {{ __('Reset Password') }} <i class="fa fa-arrow-right pl-1"></i>
        </button>
    </form>
</x-auth>


