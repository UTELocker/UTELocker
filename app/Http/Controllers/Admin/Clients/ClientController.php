<?php

namespace App\Http\Controllers\Admin\Clients;

use App\Classes\CommonConstant;
use App\Classes\Reply;
use App\DataTables\ClientsDataTable;
use App\Http\Controllers\Controller;
use App\Models\LanguageSetting;
use App\Services\Admin\Clients\ClientService;
use App\Services\Admin\Users\UserService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private ClientService $clientService;
    private UserService $userService;

    public function __construct(ClientService $clientService, UserService $userService)
    {
        $this->pageTitle = 'Client';
        $this->clientService = $clientService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ClientsDataTable $dataTable)
    {
        return $dataTable->render(
            'admin.clients.index',
            ['pageTitle' => $this->pageTitle]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = 'Create Client';
        $this->view = 'admin.clients.ajax.create';
        $this->client = $this->clientService->new();
        $this->user = $this->userService->new();
        $this->languages = LanguageSetting::where('enabled', CommonConstant::DATABASE_YES)->get();

        if (request()->ajax()) {
            if (request('quick-form') == 1) {
                return view('admin.clients.ajax.quick-create', $this->data);
            }

            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.clients.create', $this->data);
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
