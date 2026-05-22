<?php

namespace App\Http\Controllers;

use App\Models\purchase_items;
use App\Http\Requests\Storepurchase_itemsRequest;
use App\Http\Requests\Updatepurchase_itemsRequest;

class PurchaseItemsController extends Controller
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
    public function store(Storepurchase_itemsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(purchase_items $purchase_items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase_items $purchase_items)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepurchase_itemsRequest $request, purchase_items $purchase_items)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(purchase_items $purchase_items)
    {
        //
    }
}
