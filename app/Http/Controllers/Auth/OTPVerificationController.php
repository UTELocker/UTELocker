<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OTPVerificationController extends Controller
{
    public function show(Request $request): View
    {
        return view('auth.verify-phone',
            [
                'phone' => user()->mobile,
            ]
        );
    }

    public function store(Request $request)
    {
        $mobile = user()->mobile;
        if (!str_starts_with($mobile, '+84')) {
            $mobile = '+84' . substr($mobile, 1);
        }
        if ($mobile != $request->phone) {
            return Reply::error('Phone number is not matched');
        }
        $request->session()->put('isVerificationPhone', true);
        return  Reply::success('Verified phone number successfully');
    }
}
