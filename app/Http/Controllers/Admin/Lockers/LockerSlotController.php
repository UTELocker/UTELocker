<?php

namespace App\Http\Controllers\Admin\Lockers;

use App\Classes\Reply;
use App\Enums\LockerSlotStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lockers\UpdateSlotRequest;
use App\Services\Admin\Lockers\LockerService;
use App\Services\Admin\Lockers\LockerSlotService;
use Illuminate\Http\Request;

class LockerSlotController extends Controller
{
    private LockerSlotService $lockerSlotService;
    private LockerService $lockerService;

    public function __construct(LockerSlotService $lockerSlotService, LockerService $lockerService)
    {
        parent::__construct();
        $this->lockerSlotService = $lockerSlotService;
        $this->lockerService = $lockerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($locker)
    {
        dd($locker);
    }

    public function bulkUpdate(Request $request, $locker)
    {
        $form = $request->all();
        $locker = $this->lockerService->get($locker);
        $oldSlots = $this->lockerService->getModules($locker);
        $this->lockerSlotService->bulkUpdate($locker->id, $oldSlots, $form);
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
    public function edit(string $lockerId, string $slotId)
    {
        $this->pageTitle = 'Edit Slot';
        $this->view = 'admin.lockers.slots.bulk-update';
        $this->lockerId = $lockerId;
        $this->slot = $this->lockerSlotService->get($slotId);

        return view($this->view, $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSlotRequest $request, string $lockerId, string $slotId)
    {
        $form = $request->all();
        $this->lockerSlotService->updateStatus($slotId, $form['status']);

        return Reply::success('Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
