<?php

namespace App\Http\Controllers\Admin\Users;

use App\Classes\Reply;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Services\Admin\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->pageTitle = 'Users';
    }

    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index', $this->data);
    }

    public function create()
    {
        $this->pageTitle = 'Create User';
        $this->view = 'admin.users.ajax.create';
        $this->user = $this->userService->new();

        if (request()->ajax()) {
            if (request('quick-form') == 1) {
                return view('admin.users.ajax.quick-create', $this->data);
            }

            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.users.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
