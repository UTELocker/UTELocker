<?php

namespace App\Http\Controllers\Admin\Lockers;

use App\Classes\Reply;
use App\DataTables\LockersDataTable;
use App\Enums\LockerStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lockers\StoreLockerRequest;
use App\Http\Requests\Admin\Lockers\UpdateLockerRequest;
use App\Models\Location;
use App\Models\Locker;
use App\Models\User;
use App\Services\Admin\Bookings\BookingService;
use App\Services\Admin\Locations\LocationService;
use App\Services\Admin\Lockers\LockerService;
use App\Services\Wallets\TransactionService;
use App\Traits\HandleNotification;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    private LockerService $lockerService;
    private LocationService $locationService;
    private BookingService $bookingService;
    private TransactionService $transactionService;
    use HandleNotification;

    public function __construct(
        LockerService $lockerService,
        LocationService $locationService,
        BookingService $bookingService,
    ){
        parent::__construct();
        $this->pageTitle = __('modules.lockers.title');
        $this->lockerService = $lockerService;
        $this->locationService = $locationService;
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LockersDataTable $dataTable)
    {
        $this->locations = $this->locationService->getLocationsOfClient();
        return $dataTable->render('admin.lockers.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = __('modules.lockers.create');
        $this->view = 'admin.lockers.ajax.create';
        $this->locker = $this->lockerService->new();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.lockers.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLockerRequest $request)
    {
        $form = $request->all();
        $locker = $this->lockerService->add($form);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.lockers.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->locker = $this->lockerService->get($id);
        $this->pageTitle = $this->locker->code;

        $tab = request('tab');

        switch ($tab) {
            case 'slots':
                $this->view = 'admin.lockers.slots.index';
                break;
            case 'bulk-create':
                return $this->bulkCreate();
                break;
            default:
                $this->location = $this->locker->location;
                $this->license = $this->locker->license;
                $this->slots = $this->locker->lockerSlotAvailable;
                $this->numBooking = $this->bookingService->numBooking($this->locker);
                $this->sumEarn = $this->bookingService->sumEarn($this->locker);
                $this->view = 'admin.lockers.ajax.details';
                break;
        }

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->activeTab = $tab ?: 'details';

        return view('admin.lockers.show', $this->data);
    }

    private function bulkCreate()
    {
        $this->view = 'admin.lockers.slots.bulk-create';
        $this->modules = $this->lockerService->getModules(locker: $this->locker);
        $this->isEdit = user()->isSuperUser();
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->activeTab = 'bulk-create';
        return view('admin.lockers.show', $this->data);
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
        $this->locker = $this->lockerService->get($id);
        $this->locations = $this->locationService->getLocationsOfLocker($this->locker);
        $this->pageTitle = __('app.update') . ' ' . __('app.locker');

        if (request()->ajax()) {
            $this->view = 'admin.lockers.ajax.edit';
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'admin.lockers.ajax.edit';

        return view('admin.lockers.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLockerRequest $request, string $id)
    {
        $this->editPermission = User::canAccess(UserRole::ADMIN);
        if (!$this->editPermission) {
            abort(403);
        }
        $form = $request->all();
        $this->locker = $this->lockerService->get($id);
        $res = $this->lockerService->update($this->locker, $form);

        if (isset($res['status']) && $res['status'] == 'error') {
            return Reply::error($res['message']);
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.lockers.index');
        }

        if($request->add_more == 'true') {
            $html = $this->create();
            return Reply::successWithData(__('messages.recordSaved'), ['html' => $html, 'add_more' => true]);
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
