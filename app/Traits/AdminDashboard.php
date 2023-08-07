<?php

namespace App\Traits;

trait AdminDashboard
{
    public function adminDashboard()
    {
        return view('admin.dashboard.index', $this->data);
    }
}
