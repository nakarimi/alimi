<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use App\Models\Item;

use App\Models\Sale;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\ProItem;
use App\Models\Project;
use App\Models\Storage;
use App\Models\Currency;
use App\Models\SaleFour;
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

class SaleFourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return SaleFour::with('storage')->get();
        return Sale::with(['saleS4.storage'])->has('saleS4.storage')->with('source')->has('source')->get();
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
            $serial_no = Helper::getSerialNo('sale4', 'sale');
            $this->validate($request, [
                'serial_no' => 'required',
                'service_cost' => 'required',
                'additional_cost' => 'required',
                'total' => 'required',
                // 'description' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;

            // $project = $request->project_id;
            foreach (['source_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $request['serial_no'] = $serial_no->value;
            $newSale = Sale::create($request->all());

            $request['sales_id'] = $newSale->id;
            $newSaleFour = SaleFour::create($request->all());

            $typeId = AccountType::latest()->first()->id;
            $accData = [
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => $typeId,
                'name' => 'اکانت ساخته شده برای فروشات',
                'ref_code' => 'فروشات - ' . $newSale->id,
                'status' => 1,
                'description' => 'اکانت ساخته شده برای فروشات',
                'system' => 0,
            ];
            $newAcc = Account::find(config('app.general_customers_account'));
            if ($newAcc) {

                $newFR = Helper::createDoubleFR('sale4', $newSale, $newAcc, $request);
            }
            if ($newAcc) {
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale4', $request->item, $newSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }

            // Create the Notification
            if ($newFR) {
                $client_name = $request['client_name'];
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
            return [$newSale, $newSaleFour, $newAcc, $newFR, $newNotif, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleFour  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function show(SaleFour $saleOne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleFour  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::with([
            'saleS4',
        ])->find($id);
        $sale_items = [];
        $sale_items = StockRecord::with([
                'item_id.uom_equiv_id',
                'item_id.uom_id',
                'item_id.type',
                'operation_id',
                'uom_equiv_id',
                'uom_id',
            ])
            ->where('type', 'sale')->where('type_id', $id)->get();
        $sale['items'] = $sale_items;

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
     * @param  \App\SaleFour  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'serial_no' => 'required',
                'service_cost' => 'required',
                'additional_cost' => 'required',
                'total' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;
            foreach (['source_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $rootSale = Sale::find($id);
            $rootSale->update($request->all());
            $rootSale = Sale::find($id);

            $saleFour = SaleFour::where('sales_id', $id)->first();
            $request['sales_id'] = $rootSale->id;
            $saleFour->update($request->all());
            $saleFour = SaleFour::where('sales_id', $id)->first();

            $customerAcc = Account::find(config('app.general_customers_account'));
            if ($customerAcc) {
                StockRecord::where('type', 'sale4')
                ->where('type_id', $id)
                ->delete();
                FinancialRecord::where('type', 'sale4')
                ->where('type_id', $id)
                ->delete();

                $newFR = Helper::createDoubleFR('sale4', $rootSale, $customerAcc, $request);
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale4', $request->item, $rootSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }

            DB::commit();
            return [$rootSale, $saleFour, $customerAcc, $newFR, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }

    }
}
