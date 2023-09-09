<?php

namespace App\DataTables;

use App\Models\Client;
use App\Models\Locker;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\View\Components\Locker as LockerComponent;
use App\View\Components\Client as ClientComponent;

class LockersDataTable extends BaseDataTable
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
        $datatables->addColumn('action', function ($row) {
            return view('admin.lockers.actions', compact('row'));
        });

        $datatables->addColumn('code', function ($row) {
            return ucfirst($row->code);
        });

        $datatables->editColumn('code', function ($row) {
            return (new LockerComponent($row))->render();
        });

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

        $datatables->addColumn('created_at', function ($row) {
            return $row->created_at->format(globalSettings()->date_format);
        });

        $datatables->addIndexColumn();
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        $datatables->rawColumns(['code', 'action', 'check']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Locker $model): QueryBuilder
    {
        $query = $model
            ->newQuery()
            ->leftJoin('licenses', 'licenses.locker_id', '=', 'lockers.id')
            ->leftJoin('clients', 'clients.id', '=', 'licenses.client_id')
            ->select([
                'lockers.*',
                'clients.name as client_name',
                'clients.app_name as client_app_name',
                'clients.id as client_id',
                'clients.logo as client_logo',
            ])
            ->withCount('lockerSlotType');

        if (!auth()->user()->isSuperUser()) {
            $query->where('licenses.client_id', auth()->user()->client_id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
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
            __('app.code') => ['data' => 'code', 'name' => 'code', 'title' => __('app.code')],
            __('aPpp.client') => ['data' => 'client', 'name' => 'client', 'title' => __('app.client')],
            __('app.lockerSlots') => [
                'data' => 'locker_slot_type_count',
                'name' => 'locker_slot_type_count',
                'title' => __('app.lockerSlots')
            ],
            __('app.createdAt') => ['data' => 'created_at', 'name' => 'created_at', 'title' => __('app.createdAt')]
        ];

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
