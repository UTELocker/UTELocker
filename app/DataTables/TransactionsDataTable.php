<?php

namespace App\DataTables;

use App\Classes\Common;
use App\Enums\TransactionType;
use App\Models\Client;
use App\Models\Transaction;
use App\View\Components\Client as ClientComponent;
use App\View\Components\Datatable\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionsDataTable extends BaseDataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();

        $datatables->addColumn('action', function ($row) {
            return view('admin.transactions.actions', compact('row'));
        });

        $datatables->editColumn('type', function ($row) {
            return TransactionType::getDescription($row->type);
        });

        $datatables->addColumn('status', function ($row) {
            return view('admin.transactions.status', compact('row'));
        });

        $datatables->editColumn('time', function ($row) {
            return Common::formatDateBaseOnSetting($row->time, user()->isSuperUser());
        });

        $datatables->editColumn('amount', function ($row) {
            return number_format($row->amount);
        });

        $datatables->addColumn('client_name', function ($row) {
            return "<a href='" . route('admin.clients.show', $row->client_id) . "'>" . $row->client_name . "</a>";
        });

        $datatables->rawColumns(['status', 'client_name', 'action']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        $paymentMethodName = $this->request->get('payment_method_name');
        $status = $this->request->get('status');
        $type = $this->request->get('type');
        return $model->newQuery()
            ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
            ->when(!user()->isSuperUser(), function ($query) {
                return $query->where('users.client_id', user()->client_id);
            })
            ->select(
                'transactions.*',
                'users.name as user_name',
                'payment_methods.name as payment_method_name',
                'clients.name as client_name', 'clients.id as client_id'
            )
            ->when($paymentMethodName !== null, function ($query) use ($paymentMethodName) {
                return $query->where('payment_methods.name', 'like', '%' . $paymentMethodName . '%');
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('transactions.status', $status);
            })
            ->when($type !== null, function ($query) use ($type) {
                return $query->where('transactions.type', $type);
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->setBuilder('lockers-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["lockers-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                  //
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> '
                . __('app.exportExcel')]));
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $data = [
            '#' => [
                'data' => 'DT_RowIndex',
                'orderable' => false,
                'searchable' => false,
                'title' => '#'
            ],
            __('modules.transactions.user') => ['data' => 'user_name', 'name' => 'user_name', 'title' => __('modules.transactions.user')],
            __('modules.paymentMethod.name') => ['data' => 'payment_method_name', 'name' => 'payment_method_name', 'title' => __('modules.paymentMethod.name')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
            __('modules.transactions.type') => ['data' => 'type', 'name' => 'type', 'title' => __('modules.transactions.type')],
            __('modules.transactions.amount') => ['data' => 'amount', 'name' => 'amount', 'title' => __('modules.transactions.amount')],
            __('modules.transactions.createdAt') => ['data' => 'time', 'name' => 'time', 'title' => __('modules.transactions.createdAt')],
        ];

        if (user()->isSuperUser()) {
            $data = array_merge($data, [
                __('app.client') => ['data' => 'client_name', 'name' => 'client_name', 'title' => __('app.client')],
            ]);
        }

        $action = [
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        return array_merge($data, $action);
    }
}
