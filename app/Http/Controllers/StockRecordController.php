<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\StockRecord;
use Illuminate\Http\Request;

class StockRecordController extends Controller
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
     * @param  \App\Models\StockRecord  $stockRecord
     * @return \Illuminate\Http\Response
     */
    public function show(StockRecord $stockRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockRecord  $stockRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(StockRecord $stockRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockRecord  $stockRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockRecord $stockRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockRecord  $stockRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockRecord $stockRecord)
    {
        //
    }

    public function itemRecords(Request $request)
    {
        if ($request->source_id) {
            $inc = StockRecord::where('source', $request->source)
                ->where('source_id', $request->source_id)
                ->where('item_id', $request->item_id)
                ->sum('increment_equiv');
            $dec = StockRecord::where('source', $request->source)
                ->where('source_id', $request->source_id)
                ->where('item_id', $request->item_id)
                ->sum('decrement_equiv');

            return $inc - $dec;
        } else {
            $inc = StockRecord::where('item_id', $request->item_id)
                ->sum('increment_equiv');
            $dec = StockRecord::where('item_id', $request->item_id)
                ->sum('decrement_equiv');
            return $inc - $dec;
        }
    }
}
