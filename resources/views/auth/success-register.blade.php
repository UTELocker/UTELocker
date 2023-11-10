<x-auth>
    <h3>Thank you {{ $user->name }} for registering an account</h3>
    @if ($user->active == \App\Classes\CommonConstant::DATABASE_YES)
        <p>
            Your account is now active. You can login using your email and password.
        </p>
        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}">
                <button type="button" id="submit-register"
                        class="btn-primary f-w-500 rounded w-100 height-50 f-18 ">
                    {{ __('app.login') }} <i class="fa fa-arrow-right pl-1"></i>
                </button>
            </a>
        </div>
    @else
        <p>
            Your account is not active yet. Please wait for the admin to activate your account.
        </p>
    @endif
    <x-slot name="scripts">
        <script>
        </script>
    </x-slot>
</x-auth>
