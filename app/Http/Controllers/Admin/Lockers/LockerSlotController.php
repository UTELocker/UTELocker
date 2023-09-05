<?php

namespace App\Http\Controllers\Admin\Lockers;

use App\Http\Controllers\Controller;
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
