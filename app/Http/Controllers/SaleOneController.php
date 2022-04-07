<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

use App\Models\Sale;
use App\Helper\Helper;
use App\Models\Account;

use App\Models\ProItem;
use App\Models\Project;
use App\Models\SaleOne;
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

class SaleOneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sales1 = Sale::with(['saleS1.project', 'source'])->get();
        $sales2 = Sale::with(['saleS2.storage', 'source'])->get();

        // $result = array_merge( $sales1, $sales2 );
        // $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
        // sort( $result );

        return [$sales1, $sales2];
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
                // 'title' => 'required|min:2',
                // 'formula' => 'required|min:2',
                // 'serial_no' => 'required',
                // 'steps' => 'required',
                // 'description' => 'required',
                // 'destination' => 'required',
                // 'source_type' => 'required',
                'project_id' => 'required',
                'transport_cost' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'deposit' => 'required',
                'total' => 'required',
                'type' => 'required',
                'source_id' => 'required',
                'user_id' => 'required',
                'currency_id' => 'required',
                'datatime' => 'required',
                'item' => 'required',
            ]);

            $project = $request->project_id;
            $storage = $request->source_id;
            $request['serial_no'] = $serial_no->value;
            foreach (['source_id', 'project_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $newSale = Sale::create($request->all());
            $request['sales_id'] = $newSale->id;
            $newSaleOne = SaleOne::create($request->all());

            // $typeId = AccountType::latest()->first()->id;
            // $accData = [
            //     'user_id' => auth()->guard('api')->user()->id,
            //     'type_id' => $typeId,
            //     'name' => 'اکانت ساخته شده برای فروشات',
            //     'ref_code' => 'فروشات - ' . $newSale->id,
            //     'status' => 1,
            //     'description' => 'اکانت ساخته شده برای فروشات',
            //     'system' => 0,
            // ];
            $newAcc = Account::where('type_id', config('app.contract_account_type'))->where('ref_code', $project['id'])->first();
            if ($newAcc) {
                $newFR = Helper::createDoubleFR('sale', $newSale, $newAcc, $request);
            }
            if ($newAcc) {
                $stocks = [];
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale', $request->item, $newSale, $storage, $request, $totalmoney, $request['source_type'], $storage['id']);
            }

            // Create the Notification
            if ($newFR) {
                $client_name = $project['pro_data']['client']['name'];
                $item_name = $storage['name'];
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
                if ($newNotif) {
                    Helper::createUserAssign($newNotif->id, "nor");
                }
            }
            DB::commit();
            return [$newSale, $newSaleOne, $newAcc, $newFR, $newNotif, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleOne  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        if ($sale->type == "s1") {
            $sales1 = Sale::join('sales_ones AS s', 'sales.id', '=', 's.sales_id')
                ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id")->where('s.sales_id', $id)->get();
        } else if ($sale->type == "s2") {
            $sales1 = Sale::join('sales_twos AS s1', 'sales.id', '=', 's1.sales_id')
                ->selectRaw("s1.sales_id, s1.serial_no, s1.total, s1.service_cost, sales.type, sales.source_type, sales.source_id")->where('s1.sales_id', $id)->get();
        } else if ($sale->type == "s3") {
            $sales1 = Sale::join('sales_threes AS s', 'sales.id', '=', 's.sales_id')
                ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id")->where('s.sales_id', $id)->get();
        } else if ($sale->type == "s4") {
            $sales1 = Sale::join('sales_fours AS s', 'sales.id', '=', 's.sales_id')
                ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id")->where('s.sales_id', $id)->get();
        }

        return $sales1;
    }
    public function showSale($id)
    {
        $base = Sale::findOrFail($id);
        if ($base->type == "s1") {
            $sale = Sale::with(['saleS1.project.pro_data', 'source_id'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS1;
            $st_type = 'sale';
        } else if ($base->type == "s2") {
            $sale = Sale::with(['saleS2.client', 'source_id'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS2;
            $st_type = 'sale2';
        } else if ($base->type == "s3") {
            $sale = Sale::with(['saleS3.project.pro_data', 'source_id'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS3;
            $st_type = 'sale3';
        } else if ($base->type == "s4") {
            $sale = Sale::with(['saleS4.client', 'source_id'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS4;
            $st_type = 'sale4';
        }

        $sale['m16s'] = DB::table('m16s')->where('sale_id', $sale->id)->where('sale_type', $sale->type)->first();
        $sale['sale_checklists'] = DB::table('sale_checklists')->where('sale_id', $sale->id)->where('sale_type', $sale->type)->first();
        $sale['financial_records'] = DB::table('financial_records')->where('type_id', $sale->id)->where('type', $st_type)->first();
        // $sale['financial_records'] = 1;


        $sale->currency_id = Currency::find($sale->currency_id);
        $sale->bank_account = Account::find($sale->bank_account);
        $sale['stock_records'] = StockRecord::where('type', $st_type)->where('type_id', $sale->id)
            ->with([
                'item_id.type',
                'item_id.uom_equiv_id',
                'item_id.uom_id',
                'uom_equiv_id',
                'uom_id',
                'operation_id',
                'measur_unit',
                'source_id'
            ])->get();


        return $sale;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleOne  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale = Sale::with([
            'saleS1.project',
            'saleS1.project.pro_data.client',
            'saleS1.project.pro_data.company_id',
            // 'saleS1.project.pro_items.item_id',
            // 'saleS1.project.pro_items.item_id.uom_equiv_id',
            // 'saleS1.project.pro_items.item_id.uom_id',
            // 'saleS1.project.pro_items.item_id.type',
            // 'saleS1.project.pro_items.operation_id',
            'saleS1.project.proposal_id.pro_data.client'
        ])->find($id);
        $sale['saleS1']['pro_items'] = StockRecord::with([
            'operation_id',
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
        ])->where('type', 'sale')->where('type_id', $id)->get();

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
     * @param  \App\SaleOne  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // $serial_no = Helper::getSerialNo('proj-' . $request->project_id['id'], 'sale');
            $this->validate($request, [
                'project_id' => 'required',
                'transport_cost' => 'required',
                'service_cost' => 'required',
                'tax' => 'required',
                'deposit' => 'required',
                'total' => 'required',
                'type' => 'required',
                'source_id' => 'required',
                'user_id' => 'required',
                'currency_id' => 'required',
                'datatime' => 'required',
                'item' => 'required',
            ]);

            $project = $request->project_id;
            $storage = $request->source_id;
            foreach (['source_id', 'project_id'] as $key) {
                $request[$key] = $request[$key]['id'];
            }
            $rootSale = Sale::find($id);
            $d = [
                'type' => $request->type,
                'source_id' => $request->source_id,
                'source_type' => $request->source_type,
                'user_id' => auth()->guard('api')->user()->id,
                'currency_id' => $request->currency_id,
                'bank_account' => $request->bank_account,
                'datatime' => $request->datatime,
            ];
            $rootSale->update($d);
            $rootSale = Sale::find($id);

            $saleOne = SaleOne::where('sales_id', $id)->first();
            $request['sales_id'] = $rootSale->id;
            $saleOne->update($request->all());
            $saleOne = SaleOne::where('sales_id', $id)->first();
            $projectAcc = Account::where('type_id', config('app.contract_account_type'))->where('ref_code', $project['id'])->first();
            if ($projectAcc) {
                // Or delete from this ids $request['items_to_delete']
                StockRecord::where('type', 'sale')
                    ->where('type_id', $id)
                    ->delete();
                FinancialRecord::where('type', 'sale')
                    ->where('type_id', $id)
                    ->delete();

                $newFR = Helper::createDoubleFR('sale', $rootSale, $projectAcc, $request);
                $stocks = null;
                $totalmoney = 0;
                $stocks = Helper::salesCreateStockRecords('sale', $request->item, $rootSale, $storage, $request, $totalmoney, $request['source_type'], $storage['id']);
            }

            DB::commit();
            return [$rootSale, $saleOne, $projectAcc, $newFR, $stocks];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleOne  $saleOne
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);
            Helper::deleteSales($sale->id, $sale->type);
            $sale->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function allSales()
    {
        $sales1 = Sale::join('sales_ones AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

        $sales2 = Sale::join('sales_twos AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

        $sales3 = Sale::join('sales_threes AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

        $sales4 = Sale::join('sales_fours AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.serial_no, s.total, s.additional_cost as service_cost, sales.type, sales.source_type, sales.source_id");

        $sales_data = $sales1->union($sales2)->union($sales3)->union($sales4)->orderBy('sales_id', 'desc')->get();
        foreach ($sales_data as $key => $sale) {
            $base = Sale::findOrFail($sale['sales_id']);
            if ($base->type == "s1") {
                $sale = Sale::with(['saleS1.project.pro_data', 'source_id'])->where('id', $sale['sales_id'])->first();
                $sales_data[$key]['sales'] = $sale->saleS1;
            } else if ($base->type == "s3") {
                $sale = Sale::with(['saleS3.project.pro_data', 'source_id'])->where('id', $sale['sales_id'])->first();
                $sales_data[$key]['sales'] = $sale->saleS3;
            }
        }
        return $sales_data;
    }

    public function saleCheckList(Request $request)
    {
        $q = DB::table('sale_checklists')->where('sale_id', $request->sale_id)
            ->where('sale_type', $request->sale_type);
        $data = [
            'official_steps' => $request->official_steps,
            'mof_gov' => $request->mof_gov,
            'bank_operations' => $request->bank_operations,
            'receive_money' => $request->receive_money,
            'get_documents' => $request->get_documents,
            'sale_id' => $request->sale_id,
            'sale_type' => $request->sale_type,
        ];
        if ($q->first()) {
            $r = $q->update($data);
        } else {
            $r = DB::table('sale_checklists')->insert($data);
        }
        return $r;
    }
    public function s2CostFormUpdate(Request $request)
    {
        $q = DB::table('sales_twos')->where('sales_id', $request->sale_id);
        $data = [
            'transport_cost' => $request->transport_cost,
            'service_cost' => $request->service_cost,
        ];
        if ($q->first()) {
            $r = $q->update($data);
        }
    }
    public function saleInfoConfirmation(Request $request, $type, $id)
    {
        if ($request->form) {
            $q = DB::table('sale_checklists')->where('sale_id', $request->form['sale_id'])
                ->where('sale_type', $request->form['sale_type']);
            $data = [
                'get_documents' => $request->form['get_documents'],
                'sale_id' => $id,
                'sale_type' => $type,
            ];
            if ($q->first()) {
                $r = $q->update($data);
            } else {
                $r = DB::table('sale_checklists')->insert($data);
            }
        }
        if ($type == 's1') {
            $type = 'sale';
        } else
        if ($type == 's2') {
            $type = 'sale2';
        } else
        if ($type == 's3') {
            $type = 'sale3';
        } else
        if ($type == 's4') {
            $type = 'sale4';
        }
        DB::table('financial_records')->where('type_id', $id)
            ->where('type', $type)->update([
                'valid' => $request->confirmed,
            ]);
    }
    public function m16Store(Request $request)
    {
        DB::table('m16s')->where('sale_id', $request->sale_id)
            ->where('sale_type', $request->sale_type)->delete();
        $r = DB::table('m16s')->insert([
            'm16_number' => $request->m16_number,
            'delay_charge' => $request->delay_charge,
            'tax' => $request->tax,
            'deposit' => $request->deposit,
            'description' => $request->description,
            'sale_id' => $request->sale_id,
            'sale_type' => $request->sale_type,
        ]);
        return $r;
    }
}
