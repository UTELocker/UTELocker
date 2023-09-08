<?php

namespace App\Http\Controllers\Admin\Licenses;

use App\DataTables\LicensesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Licenses';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LicensesDataTable $dataTable)
    {
        return $dataTable->render('admin.licenses.index', $this->data);
    }
}
