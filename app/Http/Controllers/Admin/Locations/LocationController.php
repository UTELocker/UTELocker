<?php

namespace App\Http\Controllers\Admin\Locations;

use App\DataTables\LocationsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('modules.locations.title');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LocationsDataTable $dataTable)
    {
        return $dataTable->render('admin.locations.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
