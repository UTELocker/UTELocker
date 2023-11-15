<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Classes\Reply;
use App\DataTables\LocationTypesDataTable;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Locations\StoreLocationTypeRequest;
use App\Models\Client;
use App\Models\User;
use App\Services\Admin\Locations\LocationTypeSerivce;
use Illuminate\Http\Request;

class LocationTypeController extends Controller
{
    private LocationTypeSerivce $locationTypeService;

    public function __construct(LocationTypeSerivce $locationTypeService)
    {
        parent::__construct();
        $this->pageTitle = __('modules.locationTypes.title');
        $this->locationTypeService = $locationTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LocationTypesDataTable $dataTable)
    {
        return $dataTable->render('admin.location-types.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = 'Create Location Type';
        $this->view = 'admin.location-types.ajax.create';
        $this->clients = Client::getClientList();

        return view('admin.location-types.ajax.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationTypeRequest $request)
    {
        $this->locationTypeService->add($request->all());

        return Reply::redirect(route('admin.location.types.index'), __('messages.locationTypeAdded'));
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
        $this->pageTitle = 'Edit Location Type';
        $this->view = 'admin.location-types.ajax.edit';
        $this->locationType = $this->locationTypeService->get($id);
        $this->clients = Client::getClientList();
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.location-types.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLocationTypeRequest $request, string $id)
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $form = $request->all();
        $this->locationType = $this->locationTypeService->get($id);
        $this->locationTypeService->update($this->locationType, $form);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.location.types.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($this->locationTypeService->delete($id)) {
            return Reply::success(__('messages.recordDeleted'));
        }
        return Reply::error(
            'Location Type is linked with Location. Please delete location first.',
        );
    }
}
