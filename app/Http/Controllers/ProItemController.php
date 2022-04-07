<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\ProItem;
use Illuminate\Http\Request;

class ProItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProItem  $proItem
     * @return \Illuminate\Http\Response
     */
    public function show(ProItem $proItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProItem  $proItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ProItem $proItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProItem  $proItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProItem $proItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProItem  $proItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProItem $proItem)
    {
        return $proItem->delete();
    }
}
