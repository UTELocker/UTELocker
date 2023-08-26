<?php

namespace App\Http\Controllers\Admin\Clients;

use App\Classes\CommonConstant;
use App\Classes\Reply;
use App\DataTables\ClientsDataTable;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Clients\StoreClientRequest;
use App\Models\LanguageSetting;
use App\Models\User;
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
     * @throws ApiException
     */
    public function store(StoreClientRequest $request)
    {
        $form = $request->all();
        $userData = $this->getDataWithPrefix(UserService::FORM_PREFIX, $form);
        $clientData = $this->getDataWithPrefix(ClientService::FORM_PREFIX, $form);
        $client = $this->clientService->add($clientData, ['isPrefix' => true]);
        $userData['client_id'] = $client->id;
        $userData['type'] = User::ROLE_ADMIN;
        $this->userService->add($userData, ['isPrefix' => true]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.clients.index');
        }

        if($request->add_more == 'true') {
            $html = $this->create();
            return Reply::successWithData(__('messages.recordSaved'), ['html' => $html, 'add_more' => true]);
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    private function getDataWithPrefix($prefix, $form): array
    {
        return array_filter($form, function ($key) use ($prefix) {
            return str_starts_with($key, $prefix);
        }, ARRAY_FILTER_USE_KEY);
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
