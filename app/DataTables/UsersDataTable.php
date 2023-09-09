<?php

namespace App\DataTables;

use App\Models\Client;
use App\Models\User;
use App\View\Components\Client as ClientComponent;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Classes\CommonConstant;
use App\View\Components\User as UserComponent;
use App\Enums\UserRole;
use App\Enums\UserGender;

class UsersDataTable extends BaseDataTable
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
            return view('admin.users.actions', compact('row'));
        });

        $datatables->editColumn(
            'status',
            function ($row) {
                if ($row->active == CommonConstant::DATABASE_YES) {
                    return ' <i class="fa fa-circle mr-1 text-light-green f-10"></i>' . __('app.active');
                }
                else {
                    return '<i class="fa fa-circle mr-1 text-red f-10"></i>' . __('app.inactive');
                }
            }
        );

        $datatables->editColumn(
            'type',
            function($row) {
                switch ($row->type) {
                    case UserRole::SUPER_USER:
                        return 'Super User';
                    case UserRole::ADMIN:
                        return 'Admin';
                    case UserRole::NORMAL:
                        return 'Normal User';
                    default:
                        return '';
                }
            }
        );

        $datatables->editColumn(
            'gender',
            function($row) {
                switch ($row->gender) {
                    case UserGender::MALE:
                        return '<i class="fas fa-mars"></i> ' . __('app.male');
                    case UserGender::FEMALE:
                        return ' <i class="fas fa-venus"></i> ' . __('app.female');
                    case UserGender::OTHER:
                        return '<i class="fas fa-venus-mars"></i> ' . __('app.others');
                    default:
                        return '';
                }
            }
        );

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

        $datatables->editColumn(
            'name',
            function($row) {
                return (new UserComponent($row))->render();
            }
        );

        $datatables->addColumn('name', function ($row) {
            return ucfirst($row->name);
        });

        $datatables->addIndexColumn();
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        $datatables->rawColumns(['name', 'action', 'status', 'check', 'gender']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        if (User::hasPermission(\App\Enums\UserRole::SUPER_USER)) {
            return $model->newQuery()
                ->leftJoin('clients', 'clients.id', '=', 'users.client_id')
                ->select([
                    'users.*',
                    'clients.name as client_name',
                    'clients.app_name as client_app_name',
                    'clients.id as client_id',
                    'clients.logo as client_logo',
                ]);
        }
        return $model->newQuery()->where('client_id', user()->client_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('users-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["users-table"].buttons().container()
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
            __('app.name') => ['data' => 'name', 'name' => 'name', 'exportable' => true, 'title' => __('app.name')],
            __('app.client') => ['data' => 'client', 'name' => 'client', 'title' => __('app.client')],
            __('app.type') => ['data' => 'type', 'name' => 'type', 'exportable' => true, 'title' => __('app.type')],
            __('app.email') => ['data' => 'email', 'name' => 'email', 'exportable' => true, 'title' => __('app.email')],
            __('app.gender') => ['data' => 'gender', 'name' => 'gender', 'exportable' => true, 'title' => __('app.gender')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
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
