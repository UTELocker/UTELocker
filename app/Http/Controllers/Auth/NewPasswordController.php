<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\PasswordResetToken;
use App\Models\User;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        $user = PasswordResetToken::where('token', $request->token)
            ->leftJoin('users', function ($join) {
                $join->on('password_reset_tokens.email', '=', 'users.email')
                    ->on('password_reset_tokens.client_id', '=', 'users.client_id');
            })
            ->leftJoin('clients', 'users.client_id', '=', 'clients.id')
            ->select('users.name', 'clients.name as client_name')
            ->firstOrFail();

        return view('auth.reset-password', ['request' => $request, 'user' => $user]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userId = User::
            leftJoin('password_reset_tokens', function ($join) {
                $join->on('password_reset_tokens.email', '=', 'users.email')
                    ->on('password_reset_tokens.client_id', '=', 'users.client_id');
            })
            ->where('password_reset_tokens.token', $request->token)
            ->select('users.id')
            ->firstOrFail();

        $user = User::where('id', $userId->id)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        if ($user) {
            PasswordResetToken::where('token', $request->token)->delete();

            $status = Password::PASSWORD_RESET;
        } else {
            $status = Password::INVALID_TOKEN;
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
