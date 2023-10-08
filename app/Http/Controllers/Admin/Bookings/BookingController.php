<?php

namespace App\Http\Controllers\Admin\Bookings;

use App\DataTables\BookingsDataTable;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Bookings';
    }

    public function index(BookingsDataTable $dataTable)
    {
        return $dataTable->render('admin.bookings.index', $this->data);
    }
}
