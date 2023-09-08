<?php

namespace App\DataTables;

use App\Models\LocationType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LocationTypesDataTable extends BaseDataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatables = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="select-table-row" id="datatable-row-'
                    . $row->id
                    . '"  name="datatable_ids[]" value="'
                    . $row->id
                    . '" onclick="UTELocker.common.dataTableRowCheck(' . $row->id . ')">';
            })
            ->addColumn('action', function ($row) {
                return view('admin.location-types.actions', compact('row'));
            })
            ->addColumn('code', function ($row) {
                return ucfirst($row->code) . ' - ' . $row->description;
            })
            ->addColumn('client_name', function ($row) {
                // link to client detail
                return "<a href='" . route('admin.clients.show', $row->client_id) . "'>" . $row->client_name . "</a>";
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format(globalSettings()->date_format);
            })
            ->smart(false)
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            });

        $datatables->rawColumns(['client_name', 'action', 'check']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LocationType $model): QueryBuilder
    {
        return $model
            ->newQuery()
            ->leftJoin('clients', 'clients.id', '=', 'location_types.client_id')
            ->select([
                'location_types.id',
                'location_types.code',
                'location_types.description',
                'location_types.created_at',
                'location_types.updated_at',
                'location_types.client_id',
                'clients.name as client_name'
            ]);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('locationtypes-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["locationtypes-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
            ]);
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
            __('app.client') => ['data' => 'client_name', 'name' => 'client_name', 'title' => __('app.client')],
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
