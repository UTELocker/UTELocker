<?php

namespace App\Http\Controllers\Admin\Licenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Licenses\LinkLicenseRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class LicenseLinkController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Link Licenses';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = 'Link Licenses';
        $this->view = 'admin.licenses.link.create';
        $this->clients = Client::getClientList();

        return view($this->view, $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkLicenseRequest $request)
    {

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
