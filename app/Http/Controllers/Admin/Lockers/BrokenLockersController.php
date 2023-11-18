<?php

namespace App\Http\Controllers\Admin\Lockers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\Lockers\LockerService;
use App\Services\Admin\Locations\LocationService;
use App\DataTables\BrokenLockersDataTable;
use App\Http\Requests\Admin\Lockers\UpdateBrokenLockerRequest;
use App\Classes\Reply;

class BrokenLockersController extends Controller
{
    private LockerService $lockerService;
    private LocationService $locationService;

    public function __construct(
        LockerService $lockerService,
        LocationService $locationService,
    ){
        parent::__construct();
        $this->pageTitle = __('modules.brokenLockers.title');
        $this->lockerService = $lockerService;
        $this->locationService = $locationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BrokenLockersDataTable $dataTable)
    {
        $this->locations = $this->locationService->getLocationsOfClient();
        return $dataTable->render('admin.brokenLockers.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function update(UpdateBrokenLockerRequest $request, string $id)
    {
        $locker = $this->lockerService->get($id);
        $form = $request->all();
        $res = $this->lockerService->update($locker, $form);
        if ($res['status'] && $res['status'] == 'error') {
            return Reply::error($res['message']);
        }
        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
