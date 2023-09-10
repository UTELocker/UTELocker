<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Classes\Reply;
use App\DataTables\PaymentMethodsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payments\StorePaymentMethodRequest;
use App\Services\Admin\Payments\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private PaymentMethodService $paymentMethodService;
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        parent::__construct();
        $this->pageTitle = 'Payment Methods';
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaymentMethodsDataTable $dataTable)
    {
        return $dataTable->render('admin.payments.payment-methods.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = 'Create Payment Method';
        $this->view = 'admin.payments.payment-methods.ajax.create';
        $this->paymentMethod = $this->paymentMethodService->new();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.payments.payment-methods.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = $this->paymentMethodService->add($request->all());

        if ($paymentMethod) {
            return Reply::redirect(
                route('admin.payment.payment-methods.edit', $paymentMethod->id),
                'Payment Method Added Successfully'
            );
        }

        return Reply::error('Something went wrong');
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
