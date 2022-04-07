<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use App\Models\Sale;

use App\Helper\Helper;
use App\Models\Account;
use App\Models\ProItem;
use App\Models\Project;
use App\Models\Storage;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\SaleThree;
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

class SaleThreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return SaleThree::with('storage')->get();
        return Sale::with(['saleS3.storage'])->has('saleS3.storage')->with('source')->has('source')->get();
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
            $serial_no = Helper::getSerialNo('proj-' . $request->project_id['id'], 'sale');
            $this->validate($request, [
                'serial_no' => 'required',
                'project_id' => 'required',
                'driver_name' => 'required',
                'plate_no' => 'required',
                'driver_phone' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'deposit' => 'required',
                'total' => 'required',
                // 'steps' => 'required',
                // 'description' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;

            $project = $request->project_id;
            foreach (['source_id', 'project_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $request['serial_no'] = $serial_no->value;
            $newSale = Sale::create($request->all());

            $request['sales_id'] = $newSale->id;
            $newSaleThree = SaleThree::create($request->all());
            $newAcc = Account::where('type_id', config('app.contract_account_type'))->where('ref_code', $project['id'])->first();
            if ($newAcc) {

                $newFR = Helper::createDoubleFR('sale3', $newSale, $newAcc, $request);
            }
            if ($newAcc) {
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale3', $request->item, $newSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }

            // Create the Notification
            if ($newFR) {
                $client_name = $project['pro_data']['client']['name'];
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
            return [$newSale, $newSaleThree, $newAcc, $newFR, $newNotif, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleThree  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::with([
            'saleS3.project',
            'saleS3.project.pro_data.client',
            'saleS3.project.pro_data.company_id',
            'saleS3.project.pro_items.item_id',
            'saleS3.project.pro_items.operation_id',
            'saleS3.project.pro_items.item_id.uom_equiv_id',
            'saleS3.project.pro_items.item_id.uom_id',
            'saleS3.project.pro_items.item_id.type',
            'saleS3.project.proposal_id.pro_data.client'
        ])->find($id);
        $sale['saleS3']['pro_items'] = StockRecord::with([
            'operation_id',
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
        ])->where('type', 'sale3')->where('type_id', $id)->get();

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleThree  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::with([
            'saleS3.project',
            'saleS3.project.pro_data.client',
            'saleS3.project.pro_data.company_id',
            'saleS3.project.pro_items.item_id',
            'saleS3.project.pro_items.operation_id',
            'saleS3.project.pro_items.item_id.uom_equiv_id',
            'saleS3.project.pro_items.item_id.uom_id',
            'saleS3.project.pro_items.item_id.type',
            'saleS3.project.proposal_id.pro_data.client'
        ])->find($id);
        $sale['saleS3']['pro_items'] = StockRecord::with([
            'operation_id',
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
        ])->where('type', 'sale3')->where('type_id', $id)->get();

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
     * @param  \App\SaleThree  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'serial_no' => 'required',
                'project_id' => 'required',
                'driver_name' => 'required',
                'plate_no' => 'required',
                'driver_phone' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'deposit' => 'required',
                'total' => 'required',
                'item' => 'required',
            ]);

            $source = $request->source_id;

            $project = $request->project_id;
            foreach (['source_id', 'project_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $rootSale = Sale::find($id);
            $rootSale->update($request->all());
            $rootSale = Sale::find($id);

            $saleThree = SaleThree::where('sales_id', $id)->first();
            $request['sales_id'] = $rootSale->id;
            $saleThree->update($request->all());
            $saleThree = SaleThree::where('sales_id', $id)->first();

            $projectAcc = Account::where('type_id', config('app.contract_account_type'))->where('ref_code', $project['id'])->first();
            if ($projectAcc) {
                StockRecord::where('type', 'sale3')
                ->where('type_id', $id)
                ->delete();
                FinancialRecord::where('type', 'sale3')
                ->where('type_id', $id)
                ->delete();

                $newFR = Helper::createDoubleFR('sale3', $rootSale, $projectAcc, $request);
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale3', $request->item, $rootSale, $source, $request, $totalmoney, $request['source_type'], $source['id']);
            }
            DB::commit();
            return [$rootSale, $saleThree, $projectAcc, $newFR, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleThree  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
