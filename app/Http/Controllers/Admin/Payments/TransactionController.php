<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Classes\Common;
use App\Classes\Reply;
use App\DataTables\TransactionsDataTable;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Services\Admin\Payments\PaymentMethodService;
use App\Services\Wallets\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;
    private PaymentMethodService $paymentMethodService;

    public function __construct(
        TransactionService $transactionService,
        PaymentMethodService $paymentMethodService
    ) {
        parent::__construct();
        $this->transactionService = $transactionService;
        $this->paymentMethodService = $paymentMethodService;
        $this->pageTitle = __('modules.transactions.title');
    }

    public function index(TransactionsDataTable $dataTable)
    {
        $this->paymentMethods = $this->paymentMethodService->getOfClient();
        return $dataTable->render('admin.transactions.index', $this->data);
    }

    public function show($id)
    {
        $this->transaction = $this->transactionService->getByReference($id)->load([
            'client',
            'paymentMethod',
        ]);
        $this->view = 'admin.transactions.ajax.show';
        $this->pageTitle = __('app.show') . ' ' . __('modules.transactions.title');
        $this->transaction->time = Common::formatDateBaseOnSetting($this->transaction->created_at, user()->isSuperUser());
        if ($this->transaction->type == TransactionType::PAYMENT) {
            $this->transaction->load([
                'booking' => function ($query) {
                    $query->with('locker');
                },
            ]);
        }
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('admin.transactions.show', $this->data);
    }
}
