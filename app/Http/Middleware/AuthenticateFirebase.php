<?php

namespace App\Http\Middleware;

use App\Classes\CommonConstant;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFirebase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (user()->is2FA == CommonConstant::DATABASE_YES &&
                !$request->session()->get('isVerificationPhone'))
            {
                if ($request->routeIs('verify-phone')) {
                    return $next($request);
                }
                return redirect()->route('verify-phone');
            }
            if ($request->routeIs('verify-phone')) {
                return redirect()->route('admin.dashboard');
            }
            return $next($request);
        }
        return redirect()->route('login');
    }
}
