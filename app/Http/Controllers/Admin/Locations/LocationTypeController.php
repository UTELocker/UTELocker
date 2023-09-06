<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Classes\Reply;
use App\DataTables\LocationTypesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Locations\StoreLocationTypeRequest;
use App\Models\Client;
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
