<?php

namespace App\DataTables;

use Illuminate\Support\Facades\File;
use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{
    public string $domHtml;

    public function __construct()
    {
        $this->domHtml = "<'row'<'col-sm-12'tr>><'d-flex'<'flex-grow-1'l><i><p>>";
    }

    public function setBuilder($table, $orderBy = 1)
    {
        $intl =  __('app.datatable');

        return parent::builder()
            ->setTableId($table)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy($orderBy)
            ->destroy(true)
            ->responsive()
            ->serverSide()
            ->stateSave(false)
            ->pageLength(10)
            ->processing()
            ->dom($this->domHtml)
            ->language($intl);
    }

    protected function filename(): string
    {
        $filename = strtolower(str()->snake(class_basename($this), '-'));

        return str_replace('data-table', '', $filename)  . now()->format('Y-m-d-H-i-s');
    }
}
