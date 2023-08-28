<?php

namespace App\DataTables;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ClientsDataTable extends BaseDataTable
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
            return view('admin.clients.actions', compact('row'));
        });

        $datatables->addColumn('name', function ($row) {
            return ucfirst($row->name);
        });

        $datatables->addColumn('created_at', function ($row) {
            return $row->created_at->format(globalSettings()->date_format);
        });

        $datatables->addIndexColumn();
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        $datatables->rawColumns(['name', 'action', 'check']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Client $model): QueryBuilder
    {
        $request = $this->request();
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('clients-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["clients-table"].buttons().container()
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
            __('app.name') => ['data' => 'name', 'name' => 'name', 'title' => __('app.name')],
            __('app.appName') => ['data' => 'app_name', 'name' => 'app_name', 'title' => __('app.appName')],
            __('app.email') => ['data' => 'email', 'name' => 'email', 'title' => __('app.email')],
            __('app.phone') => ['data' => 'phone', 'name' => 'phone', 'title' => __('app.phone')],
            __('app.address') => ['data' => 'address', 'name' => 'address', 'title' => __('app.address')],
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
