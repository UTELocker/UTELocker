<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Classes\Reply;
use App\DataTables\PaymentMethodsDataTable;
use App\Enums\PaymentMethodType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payments\StorePaymentMethodRequest;
use App\Http\Requests\Admin\Payments\UpdatePaymentMethodRequest;
use App\Libs\PaymentMethodConfig\CashPaymentMethodConfig;
use App\Libs\PaymentMethodConfig\PaymentMethodLoader;
use App\Services\Admin\Payments\PaymentMethodService;
use Illuminate\Http\Request;
use App\Classes\CommonConstant;

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

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('admin.payment.methods.edit', $paymentMethod->id);
        }

        return Reply::successWithData(__('Payment Method Added Successfully'), ['redirectUrl' => $redirectUrl]);
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
        $this->pageTitle = 'Edit Payment Method';
        $this->view = 'admin.payments.payment-methods.ajax.edit';
        $this->paymentMethod = $this->paymentMethodService->get($id);
        $this->paymentMethodConfig = PaymentMethodLoader::load(
            $this->paymentMethod->type,
            $this->paymentMethod->config
        );

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.payments.payment-methods.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, string $id)
    {
        $paymentMethod = $this->paymentMethodService->update($request->all(), $id);

        if ($paymentMethod) {
            return Reply::successWithData(
                __('Payment Method Updated Successfully'),
                ['redirectUrl' => route('admin.payment.methods.edit', $paymentMethod->id)]
            );
        }

        return Reply::error(__('Payment Method Update Failed'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->paymentMethodService->update([
            'active' => CommonConstant::DATABASE_NO,
        ], $id);
        return Reply::success(__('Payment Method Deleted Successfully'));
    }
}
