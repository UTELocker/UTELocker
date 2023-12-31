<?php

namespace App\Http\Controllers\Admin\Bookings;

use App\Classes\Common;
use App\Classes\Reply;
use App\DataTables\BookingsDataTable;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\Bookings\BookingService;
use App\Services\Admin\Lockers\LockerSlotService;
use App\Services\Admin\Locations\LocationService;

class BookingController extends Controller
{
    private BookingService $bookingService;
    private LockerSlotService $lockerSlotService;
    private LocationService $locationService;

    public function __construct(
        BookingService $bookingService,
        LockerSlotService $lockerSlotService,
        LocationService $locationService,
    ) {
        parent::__construct();
        $this->pageTitle = 'Bookings';
        $this->bookingService = $bookingService;
        $this->lockerSlotService = $lockerSlotService;
        $this->locationService = $locationService;
    }

    public function index(BookingsDataTable $dataTable)
    {
        $this->locations = $this->locationService->getLocationsOfClient();
        return $dataTable->render('admin.bookings.index', $this->data);
    }

    public function show($id){
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $this->booking = $this->bookingService->getHistoriesBookingClient($id)[0];
        $lockerSlots = $this->lockerSlotService->getByLockerId($this->booking->locker_id);
        $this->slotCode = Common::getListNameSlots($lockerSlots, $this->booking->locker_slot_id);
        $this->pageTitle = __('app.show') . ' ' . __('app.booking');
        $this->historyLine = $this->bookingService->getHistoryLine($this->booking);
        $this->booking->total_price = number_format($this->booking->total_price);
        if (request()->ajax()) {
            $this->view = 'admin.lockers.ajax.show';
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'admin.bookings.ajax.show';

        return view('admin.bookings.show', $this->data);
    }
}
