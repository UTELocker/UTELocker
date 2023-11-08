<?php

namespace App\Classes\Scheduler;

use App\Models\CustomToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpireTokenTask
{
    public function __invoke()
    {
        DB::transaction(function () {
            $customerTokens = CustomToken::get();
            foreach ($customerTokens as $customerToken) {
                if (Carbon::parse($customerToken->expired_at)->isPast()) {
                    $customerToken->delete();
                }
            }
        });
    }
}
