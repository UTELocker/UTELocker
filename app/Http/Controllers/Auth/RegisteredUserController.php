<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserGender;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Admin\Users\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private ?UserService $userService;

public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if (User::where('email', $value)
                        ->where('client_id', $request->get('client'))
                        ->exists()
                    ) {
                        $fail(__('validation.unique', ['attribute' => $attribute]));
                    }
                },
            ],
            'client_id' => 'required|exists:clients,id',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => 'required|in:' . implode(',', UserGender::getAll()),
            'mobile' => 'required|numeric|digits_between:10,11',
        ]);
        $user = $this->userService->add($request->all(), ['isPrefix' => false]);

        return view('auth.success-register', ['user' => $user]);
    }
}
