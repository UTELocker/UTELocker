<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->pageTitle = __('modules.settings.settings');
        $this->activeSettingMenu = 'settings.app';
        $this->middleware(function ($request, $next) {
            return user()->hasPermission(User::ROLE_ADMIN)
                ? $next($request)
                : redirect()->route('admin.dashboard');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tab = request('tab');
        $this->view = 'admin.settings.app.ajax.general';
        $this->activeTab = $tab ?: 'general';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly([
                'status' => 'success',
                'html' => $html,
                'title' => $this->pageTitle
            ]);
        }

        return view('admin.settings.app.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
