<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\AdminDashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use AdminDashboard;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Dashboard';
    }

    public function index()
    {
        return $this->adminDashboard();
    }
}
