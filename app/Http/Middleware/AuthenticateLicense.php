<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\License;

class AuthenticateLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $licenseId = $request->header('X-License-Id');
        $timeRequest = $request->header('X-License-Time');
        $hashCode = hash('sha512', License::getHashCode($licenseId, $timeRequest));

        if ($hashCode != $request->header('X-License-Hash')) {
            return response()->json([
                'status' => 'error',
                'message' => 'License is invalid'
            ], 401);
        }

        return $next($request);
    }
}
