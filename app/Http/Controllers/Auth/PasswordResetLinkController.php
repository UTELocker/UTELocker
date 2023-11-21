<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPassword;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'client_id' => 'required|integer',
        ]);

        $token = Str::random(60);

        DB::table('password_reset_tokens')
            ->insert([
                'email' => $request->email,
                'client_id' => $request->client_id,
                'token' => $token,
                'created_at' => now(),
            ]);

        $user = User::where('email', $request->email)
            ->where('client_id', $request->client_id)
            ->first();

        $user->notify(new ResetPassword($token));
    }
}
