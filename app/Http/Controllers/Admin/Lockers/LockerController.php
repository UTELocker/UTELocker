<?php

namespace App\Http\Controllers\Admin\Lockers;

use App\Classes\Reply;
use App\DataTables\LockersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lockers\StoreLockerRequest;
use App\Services\Admin\Lockers\LockerService;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    private LockerService $lockerService;

    public function __construct(LockerService $lockerService)
    {
        parent::__construct();
        $this->pageTitle = __('modules.lockers.title');
        $this->lockerService = $lockerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LockersDataTable $dataTable)
    {
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

        return view('admin.clients.create', $this->data);
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
                $this->view = 'admin.lockers.slots.bulk-create';
                break;
            default:
                $this->view = 'admin.lockers.ajax.show';
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
//        $this->view = 'admin.lockers.slots.bulk-create';
//        $this->pageTitle = __('modules.lockers.tabs.bulkCreate');
//
//        if (request()->ajax()) {
//            $html = view($this->view, $this->data)->render();
//
//            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
//        }
//
//        return view('admin.lockers.show', $this->data);
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
