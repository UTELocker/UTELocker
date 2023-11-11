<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Classes\Reply;
use App\DataTables\LocationsDataTable;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Locations\StoreLocationRequest;
use App\Models\Client;
use App\Models\LocationType;
use App\Models\User;
use App\Services\Admin\Locations\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        parent::__construct();
        $this->pageTitle = __('modules.locations.title');
        $this->locationService = $locationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LocationsDataTable $dataTable)
    {
        return $dataTable->render('admin.locations.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = 'Create Location';
        $this->view = 'admin.locations.ajax.create';
        $this->location = $this->locationService->new();
        $this->clients = Client::getClientList();
        $this->locationTypes = LocationType::getLocationTypeList();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.locations.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $this->locationService->add($request->all());

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.location.locations.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
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
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->pageTitle = 'Edit Location';
        $this->view = 'admin.locations.ajax.edit';
        $this->location = $this->locationService->get($id);
        $this->clients = Client::getClientList();
        $this->locationTypes = LocationType::getLocationTypeList();

        return view('admin.locations.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->locationService->update($request->all(), $id);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.location.locations.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
