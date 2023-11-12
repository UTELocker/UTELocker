<?php

namespace App\Http\Controllers\Auth;

use App\Classes\CommonConstant;
use App\Classes\Reply;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (user()->active == CommonConstant::DATABASE_NO) {
            Auth::guard('web')->logout();
            throw ValidationException::withMessages([
                'email' => __('messages.auth.not_active'),
            ]);
        }

        if (user()->is2FA == CommonConstant::DATABASE_YES)
        {
            $urlIntended = redirect()->intended()->getTargetUrl();
            $configFirebase = config('firebase.firebase');
            return redirect()->route('verify-phone', [
                'url' => $urlIntended,
                'configFirebase' => $configFirebase,
            ]);
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->forget('isVerificationPhone');

        return redirect('/');
    }
}
