<?php

namespace App\DataTables;

use App\Classes\Common;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BookingsDataTable extends BaseDataTable
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

        $datatables->addColumn('check', function ($row) {
            return '<input type="checkbox" class="select-table-row" id="datatable-row-'
                . $row->id
                . '"  name="datatable_ids[]" value="'
                . $row->id
                . '" onclick="UTELocker.common.dataTableRowCheck(' . $row->id . ')">';
        });

        $datatables->addColumn('status', function ($row) {
            return view('admin.bookings.status', compact('row'));
        });

        $datatables->addColumn('action', function ($row) {
            return view('admin.bookings.actions', compact('row'));
        });

        $datatables->addIndexColumn();
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        $datatables->editColumn('start_date', function ($row) {
            return Common::formatDateBaseOnSetting($row->start_date, user()->isSuperUser());
        });

        $datatables->editColumn('end_date', function ($row) {
            return Common::formatDateBaseOnSetting($row->end_date, user()->isSuperUser());
        });

        $datatables->editColumn('created_at', function ($row) {
            return Common::formatDateBaseOnSetting($row->created_at, user()->isSuperUser());
        });

        $datatables->addColumn('client_name', function ($row) {
            return "<a href='" . route('admin.clients.show', $row->client_id) . "'>" . $row->client_name . "</a>";
        });

        $datatables->editColumn('amount', function ($row) {
            $transaction = $row->transaction_id;
            $sumBooking = Booking::where('transaction_id', $transaction)->get()->count();
            return number_format($row->amount / $sumBooking);
        });

        $datatables->rawColumns(['action', 'check', 'status', 'client_name', 'amount']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Booking $model): QueryBuilder
    {
        return $model->newQuery()
            ->leftJoin('locker_slots', 'locker_slots.id', '=', 'bookings.locker_slot_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_slots.locker_id')
            ->leftJoin('transactions', 'transactions.id', '=', 'bookings.transaction_id')
            ->leftJoin('locations', 'locations.id', '=', 'lockers.location_id')
            ->leftJoin('clients', 'clients.id', '=', 'bookings.client_id')
            ->when(!user()->isSuperUser(), function ($query) {
                $query->where('bookings.client_id', '=', user()->client_id);
            })
            ->select(
                'bookings.id', 'bookings.status', 'bookings.start_date',
                'bookings.end_date', 'bookings.created_at',
                'lockers.code', 'locations.description as address',
                'transactions.id as transaction_id', 'transactions.amount as amount',
                'clients.name as client_name', 'clients.id as client_id'
            );
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('bookings-table', 2)
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> '
                . __('app.exportExcel')]));
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $data = [
            'check' => [
                'title' => '<input type="checkbox" name="select_alField as $customField) {
                    $data[] = [$customField->name => l_table"
                    id="select-all-table" onclick="UTELocker.common.selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            '#' => [
                'data' => 'DT_RowIndex',
                'orderable' => false,
                'searchable' => false,
                'title' => '#'
            ],
            __('modules.lockers.code') => ['data' => 'code', 'name' => 'code', 'title' => __('modules.lockers.code')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
            __('modules.locations.address') => ['data' => 'address', 'name' => 'address', 'title' => __('modules.locations.address')],
            __('modules.bookings.startDate') => ['data' => 'start_date', 'name' => 'start_date', 'title' => __('modules.bookings.startDate')],
            __('modules.bookings.endDate') => ['data' => 'end_date', 'name' => 'end_date', 'title' => __('modules.bookings.endDate')],
            __('modules.bookings.create') => ['data' => 'created_at', 'name' => 'created_at', 'title' => __('modules.bookings.create')],
            __('modules.transactions.amount') => ['data' => 'amount', 'name' => 'amount', 'title' => __('modules.transactions.amount')],
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
