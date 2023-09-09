<?php

namespace App\DataTables;

use App\Classes\Common;
use App\Models\License;
use App\View\Components\Locker as LockerComponent;
use App\View\Components\Client as ClientComponent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class LicensesDataTable extends BaseDataTable
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
                return view('admin.licenses.actions', compact('row'));
            })
            ->addColumn('locker', function ($row) {
                return (new LockerComponent($row->locker))->render();
            })
            ->addColumn('client', function ($row) {
                return $row->siteGroup ? (new ClientComponent($row->siteGroup))->render() : '';
            })
            ->addColumn('active_at', function ($row) {
                return Common::parseDate($row->active_at, globalSettings()->date_format);
            })
            ->addColumn('expire_at', function ($row) {
                return Common::parseDate($row->expire_at, globalSettings()->date_format);
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format(globalSettings()->date_format);
            })
            ->smart(false)
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            });

        $datatables->rawColumns(['locker', 'client', 'action', 'check']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(License $model): QueryBuilder
    {
        return $model->newQuery()
            ->userSiteGroup()
            ->with(['locker']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('licenses-table', 2);
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
            __('app.locker') => ['data' => 'locker', 'name' => 'locker', 'title' => __('app.locker')],
            __('app.client') => ['data' => 'client', 'name' => 'client', 'title' => __('app.client')],
            __('app.activeAt') => ['data' => 'active_at', 'name' => 'active_at', 'title' => __('app.activeAt')],
            __('app.expireAt') => ['data' => 'expire_at', 'name' => 'expire_at', 'title' => __('app.expireAt')],
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
