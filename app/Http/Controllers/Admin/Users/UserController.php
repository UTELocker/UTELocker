<?php

namespace App\Http\Controllers\Admin\Users;

use App\Classes\Files;
use App\Classes\Reply;
use App\DataTables\UsersDataTable;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\LanguageSetting;
use App\Models\User;
use App\Models\Client;
use App\Services\Admin\Users\UserService;
use App\Services\Admin\Clients\ClientService;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Enums\UserGender;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    private ClientService $clientService;

    public function __construct(UserService $userService, ClientService $clientService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->clientService = $clientService;
        $this->pageTitle = 'Users';
    }

    public function index(UsersDataTable $dataTable)
    {
        $this->viewPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->viewPermission) {
            abort(403);
        }
        return $dataTable->render('admin.users.index', $this->data);
    }

    public function create()
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->pageTitle = 'Create User';
        $this->view = 'admin.users.ajax.create';
        $this->user = $this->userService->new();
        $this->languages = LanguageSetting::getEnabledLanguages();
        $this->clients = Client::getClientList();
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
    public function store(StoreUserRequest $request)
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $form = $request->all();
        $userData = $this->getDataWithPrefix(UserService::FORM_PREFIX, $form);
        $this->userService->add($userData, ['isPrefix' => true]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.users.index');
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
        $this->viewPermission = user()->hasPermission(UserRole::NORMAL);
        $this->view = 'admin.users.ajax.profile';
        $tab = request('tab');
        switch ($tab) {
            case 'invoices':
//                return $this->invoices();
            case 'tests':
//                return $this->payments();
            default:
                $this->view = 'admin.users.ajax.profile';
        }
        $this->user = $this->userService->get($id);
        if ($this->user->type != UserRole::SUPER_USER) {
            $this->client = $this->clientService->get($this->user->client_id);
        } else {
            $this->client = NULL;
        }
        $this->languages = LanguageSetting::getEnabledLanguages();
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->activeTab = $tab ?: 'profile';
        return view('admin.users.ajax.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->user = $this->userService->get($id);
        $this->clients = Client::getClientList();
        $this->languages = LanguageSetting::getEnabledLanguages();
        $this->user_avatar = Files::getImageUrl($this->user->avatar, Files::USER_AVATAR_FOLDER, Files::USER_UPLOAD_FOLDER);
        $this->user_gender = UserGender::getDescriptions();
        $this->user_role = UserRole::getDescriptions();
        $this->pageTitle = __('app.update') . ' ' . __('app.user');

        if (request()->ajax()) {
            $this->view = 'admin.users.ajax.edit';
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'admin.users.ajax.edit';

        return view('admin.users.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }

        $this->user = $this->userService->get($id);
        if (User::hasPermission(\App\Enums\UserRole::SUPER_USER)) {
            $form = $request->all();
        } else {
            $form = $request->except(['user_client_id']);
        }
        $userData = $this->getDataWithPrefix(userService::FORM_PREFIX, $form);
        $this->userService->update($this->user, $userData, ['isPrefix' => true]);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.users.index');
        }

        return Reply::successWithData(__('messages.recordUpdated'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profile(string $id)
    {
        $this->viewPermission = user()->hasPermission(UserRole::NORMAL);
        $this->view = 'admin.users.ajax.profileSettings';
        $tab = request('tab');
        switch ($tab) {
            case 'invoices':
//                return $this->invoices();
            case 'tests':
//                return $this->payments();
            default:
                $this->view = 'admin.users.ajax.profileSettings';
        }
        $this->user = $this->userService->get($id);
        if ($this->user->type != UserRole::SUPER_USER) {
            $this->client = $this->clientService->get($this->user->client_id);
        } else {
            $this->client = NULL;
        }
        $this->languages = LanguageSetting::getEnabledLanguages();
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->activeTab = $tab ?: 'profile';
        return view('admin.users.ajax.profileSettings', $this->data);
    }

    public function qrCode(Request $request)
    {
        $url = config('app.url');
        $linkRegister = $url . '/register?token=' . $request->token;
        return view('auth.qr_code', [
            'title' => 'Scan QR Code to Register',
            'qrCode' => $linkRegister
        ]);
    }
}
