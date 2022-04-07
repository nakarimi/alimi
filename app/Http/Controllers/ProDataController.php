<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\ProData;
use Illuminate\Http\Request;

class ProDataController extends Controller
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
        return $request;
        // 'proposal_id',
        // 'project_id',
        // 'client_id',
        // 'title',
        // 'reference_no',
        // 'pr_worth',
        // 'deposit',
        // 'tax',
        // 'transit',
        // 'others',
        // 'currency_id',
        // 'total_price'
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProData  $proData
     * @return \Illuminate\Http\Response
     */
    public function show(ProData $proData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProData  $proData
     * @return \Illuminate\Http\Response
     */
    public function edit(ProData $proData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProData  $proData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProData $proData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProData  $proData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProData $proData)
    {
        //
    }
}
