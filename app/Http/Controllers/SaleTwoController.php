<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use App\Models\Sale;

use App\Helper\Helper;
use App\Models\Client;
use App\Models\Account;
use App\Models\ProItem;
use App\Models\Project;
use App\Models\SaleTwo;
use App\Models\Storage;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\AccountType;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use App\Models\Notification;
use App\Models\SerialNumber;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;
use App\Models\Fuel_despenser;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;

class SaleTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return SaleTwo::with('storage')->get();
        return Sale::with(['saleS2.storage'])->has('saleS2.storage')->with('source')->has('source')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $serial_no = Helper::getSerialNo('sale2', 'sale');
            $this->validate($request, [
                // 'title' => 'required|min:2',
                // 'formula' => 'required|min:2',
                // 'storage_id' => 'required',
                // 'deposit' => 'required',
                // 'steps' => 'required',
                // 'description' => 'required',
                'serial_no' => 'required',
                'destination' => 'required',
                'transport_cost' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'total' => 'required',
                'type' => 'required',
                'source_id' => 'required',
                'source_type' => 'required',
                'user_id' => 'required',
                'currency_id' => 'required',
                'datatime' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;

            $client = $request->client_id;
            foreach (['source_id', 'client_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $request['serial_no'] = $serial_no->value;
            $newSale = Sale::create($request->all());

            $request['sales_id'] = $newSale->id;
            $newSaleTwo = SaleTwo::create($request->all());
            $newAcc = Account::find(Client::find($client['id'])->account_id);
            if ($newAcc) {
                $newFR = Helper::createDoubleFR('sale2', $newSale, $newAcc, $request);
            }
            if ($newAcc) {
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale2', $request->item, $newSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }

            // Create the Notification
            if ($newFR) {
                $client_name = $client['name'];
                $item_name = $source['name'];
                $nofication = [
                    'title' => 'فروشات جدید',
                    'text' => 'یک فروش جدید از ' . $item_name . ' برای ' . $client_name . ' در سیستم ثبت گردید.',
                    'type' => 'normal',
                    'gen_date' => Carbon::now(),
                    'exp_date' => Carbon::now()->endOfDay(),
                    'action' => 'view',
                    'url' => 'sales?list',
                    'user_id' => auth()->guard('api')->user()->id,
                ];
                $newNotif = Notification::create($nofication);
            }
            DB::commit();
            return [$newSale, $newSaleTwo, $newAcc, $newFR, $newNotif, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleTwo  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function show(SaleTwo $saleOne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleTwo  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::with([
            'saleS2.client',
        ])->find($id);
        $sale['items'] = StockRecord::with([
                'item_id.uom_equiv_id',
                'item_id.uom_id',
                'item_id.type',
                'operation_id'
            ])
            ->where('type', 'sale2')->where('type_id', $id)->get();

        if ($sale->source_type == "FDSP") {
            $sale->source_id = Fuel_despenser::find($sale->source_id);
        } else
        if ($sale->source_type == "FSTR") {
            $sale->source_id = Fuel_station_storage::find($sale->source_id);
        } else
        if ($sale->source_type == "STOK") {
            $sale->source_id = Inventory::find($sale->source_id);
        } else
        if ($sale->source_type == "STRG") {
            $sale->source_id = Storage::find($sale->source_id);
        }

        return $sale;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleTwo  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                // 'title' => 'required|min:2',
                // 'formula' => 'required|min:2',
                // 'storage_id' => 'required',
                // 'deposit' => 'required',
                // 'steps' => 'required',
                // 'description' => 'required',
                'serial_no' => 'required',
                'destination' => 'required',
                'transport_cost' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'total' => 'required',
                'type' => 'required',
                'source_id' => 'required',
                'source_type' => 'required',
                'user_id' => 'required',
                'currency_id' => 'required',
                'datatime' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;
            $client = $request->client_id;
            foreach (['source_id', 'client_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $rootSale = Sale::find($id);
            $rootSale->update($request->all());
            $rootSale = Sale::find($id);

            $saleTwo = SaleTwo::where('sales_id', $id)->first();
            $request['sales_id'] = $rootSale->id;
            $saleTwo->update($request->all());
            $saleTwo = SaleTwo::where('sales_id', $id)->first();
            $clientAcc = Account::find(Client::find($client['id'])->account_id);
            if ($clientAcc) {
                StockRecord::where('type', 'sale2')
                ->where('type_id', $id)
                ->delete();
                FinancialRecord::where('type', 'sale2')
                ->where('type_id', $id)
                ->delete();

                $newFR = Helper::createDoubleFR('sale2', $rootSale, $clientAcc, $request);
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale2', $request->item, $rootSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }

            // Create the Notification
            // if ($newFR) {
            //     $client_name = $client['name'];
            //     $item_name = $source['name'];
            //     $nofication = [
            //         'title' => 'فروشات جدید',
            //         'text' => 'یک فروش جدید از ' . $item_name . ' برای ' . $client_name . ' در سیستم ثبت گردید.',
            //         'type' => 'normal',
            //         'gen_date' => Carbon::now(),
            //         'exp_date' => Carbon::now()->endOfDay(),
            //         'action' => 'view',
            //         'url' => 'sales?list',
            //         'user_id' => auth()->guard('api')->user()->id,
            //     ];
            //     $newNotif = Notification::create($nofication);
            // }
            DB::commit();
            return [$rootSale, $saleTwo, $clientAcc, $newFR, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleTwo  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
