<?php

namespace App\Http\Controllers;

use App\Models\medicines;
use App\Http\Requests\StoremedicinesRequest;
use App\Http\Requests\UpdatemedicinesRequest;

class MedicinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoremedicinesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(medicines $medicines)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(medicines $medicines)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemedicinesRequest $request, medicines $medicines)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(medicines $medicines)
    {
        //
    }
}
