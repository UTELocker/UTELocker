<?php

namespace App\Traits;

use App\Enums\TokenType;
use App\Models\CustomToken;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait ManageCustomToken
{
    public function createCustomToken(
        $type = TokenType::AUTH,
        $expiredAt = 148,
    ) {
        $token = Str::uuid();

        return CustomToken::create([
            'id' => $token,
            "token" => $token,
            "type" => $type,
            "expired_at" => now()->addHours($expiredAt),
            "client_id" => user()->client_id,
        ]);
    }

    public function verifyToken($token, $type = TokenType::AUTH)
    {
        $customToken = CustomToken::where("token", $token)
            ->where("type", $type)
            ->first();
        if (!$customToken) {
            return false;
        }

        if ($customToken->expired_at < now()) {
            return false;
        }

        return $customToken;
    }

    public function getTokenOfClient($type = null)
    {
        $tokens = CustomToken::where("client_id", user()->client_id)
            ->when($type, function ($query) use ($type) {
                return $query->where("type", $type);
            })
            ->get();
        return $tokens->map(function ($token) {
            $expiredAt = Carbon::parse($token->expired_at);
            $token->expired_at = $expiredAt->diffForHumans(now());
            return $token;
        });
    }

    public function deleteToken($id)
    {
        $token = CustomToken::where("id", $id)->firstOrFail();
        $token->delete();
    }
}