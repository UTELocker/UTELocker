<?php

namespace App\Http\Controllers;

class PortalController extends Controller
{
    public function index()
    {
        if (user()->isSuperUser()) {
            return redirect()->route('admin.dashboard');
        }

        return view(
            'layouts.portal',
            [
                'pageTitle' => 'UTELocker Portal',
            ]
        );
    }
}
