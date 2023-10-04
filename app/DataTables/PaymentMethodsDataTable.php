<?php

namespace App\DataTables;

use App\Classes\CommonConstant;
use App\Models\Client;
use App\Models\PaymentMethod;
use App\View\Components\Client as ClientComponent;
use App\View\Components\Datatable\Status;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class PaymentMethodsDataTable extends BaseDataTable
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
            return view('admin.payments.payment-methods.actions', compact('row'));
        });

        $datatables->addColumn('code', function ($row) {
            return ucfirst($row->code);
        });

        $datatables->editColumn(
            'status',
            function ($row) {
                return (new Status($row->active))->render();
            }
        );

        if (auth()->user()->isSuperUser()) {
            $datatables->editColumn('client', function ($row) {
                if (!$row->client_id) {
                    return '';
                }
                $client = new Client();
                $client->id = $row->client_id;
                $client->name = $row->client_name;
                $client->app_name = $row->client_app_name;
                $client->logo = $row->client_logo;
                return (new ClientComponent($client))->render();
            });
        }

        $datatables->addColumn('created_at', function ($row) {
            return $row->created_at->format(globalSettings()->date_format);
        });

        $datatables->rawColumns(['status']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PaymentMethod $model): QueryBuilder
    {
        $query = $model
            ->newQuery()
            ->leftJoin('clients', 'clients.id', '=', 'payment_methods.client_id')
            ->select(
                'payment_methods.*',
                'clients.name as client_name',
                'clients.app_name as client_app_name',
                'clients.id as client_id',
                'clients.logo as client_logo'
            );

        if (!auth()->user()->isSuperUser()) {
            $query->where('payment_methods.client_id', auth()->user()->client_id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('paymentmethods-table', 2);
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
            __('app.code') => ['data' => 'code', 'name' => 'code', 'title' => __('app.code')],
            __('app.name') => ['data' => 'code', 'name' => 'code', 'title' => __('app.name')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
            __('app.createdAt') => ['data' => 'created_at', 'name' => 'created_at', 'title' => __('app.createdAt')]
        ];

        if (auth()->user()->isSuperUser()) {
            $data = array_merge($data, [
                __('app.client') => ['data' => 'client', 'name' => 'client', 'title' => __('app.client')],
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
