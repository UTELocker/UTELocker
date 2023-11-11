<?php

namespace App\Http\Middleware;

use App\Classes\CommonConstant;
use App\Enums\ClientStatus;
use App\Enums\UserRole;
use App\Models\Client;
use App\Traits\ManageCustomToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionRegister
{
    use ManageCustomToken;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenAccess = $request->get('token_access');
        $client = $request->get('client_id');
        if ($tokenAccess && $this->verifyToken($tokenAccess,$client)) {
            return $next($request);
        }

        $clientId = $request->get('client_id');
        $client = Client::where('id', $clientId)->first();
        if (!$client) {
            return back()->withErrors(['client_id' => 'Client not found']);
        }

        if ($client->status == ClientStatus::PRIVATE) {
            return back()->withErrors(['client_id' => 'Client is private']);
        }

        if ($client->allow_signup == CommonConstant::DATABASE_NO) {
            $request->merge(['active' => CommonConstant::DATABASE_NO]);
        }

        return $next($request);
    }
}
