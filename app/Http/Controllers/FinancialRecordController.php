<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\FinancialRecord;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class FinancialRecordController extends Controller
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
     * @param  \App\Models\FinancialRecord  $financialRecord
     * @return \Illuminate\Http\Response
     */
    public function show(FinancialRecord $financialRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinancialRecord  $financialRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(FinancialRecord $financialRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinancialRecord  $financialRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FinancialRecord $financialRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinancialRecord  $financialRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialRecord $financialRecord)
    {
        //
    }

    public function byaccount(Request $request, FinancialRecord $financialRecord)
    {
        $financial_records = FinancialRecord::where('account_id', $request->id)
        // ->where('id', 205)
        ->with(['currency', 'exchange_rate'])->get();
        $total_af = 0;
        $total_usd = 0;
        Helper::calc_account_balance($financial_records, $total_af, $total_usd);

        return ['fr' => $financial_records, 'afn' => $total_af, 'usd' => $total_usd];
    }

}
