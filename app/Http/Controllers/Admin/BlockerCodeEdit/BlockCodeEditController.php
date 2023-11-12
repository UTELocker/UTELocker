<?php

namespace App\Http\Controllers\Admin\BlockerCodeEdit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\Reply;

class BlockCodeEditController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Blocker Code Edit';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blocker-code-edit.index', $this->data);
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
        try {
            $res = DB::select($request->code);
            return Reply::successWithData('Code executed successfully', ['data' => $res]);
        } catch (\Throwable $th) {
            return Reply::error($th->getMessage());
        }
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
