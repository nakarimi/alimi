<?php

namespace App\Http\Controllers;

use App\Models\Item;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Storage;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use App\Models\Notification;
use App\Models\SerialNumber;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;

use App\Models\Fuel_despenser;
use App\Models\FinancialRecord;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class PurchaseController extends Controller
{

    public function serial()
    {
        $serial_number = SerialNumber::where('type', 'pur')->latest()->first();
        if ($serial_number) {
            return $serial_number->value + 1;
        } else {
            return 101;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return config('app.cash_in_hand');
        return Purchase::with('user', 'vendor')->latest()->get();
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

            $serial_number = SerialNumber::where('type', 'pur')->latest()->first();
            if ($serial_number) {
                if ($serial_number->value > $request['serial_no']) {
                    $request['serial_no'] = $serial_number->value + 1;
                    $serial_number->value =  $request['serial_no'];
                    $serial_number->save();
                } else {
                    $serial_number->value =  $request['serial_no'];
                    $serial_number->save();
                }
            } else {
                SerialNumber::create([
                    'type' => 'pur',
                    'prefix' => 'pur',
                    'value' => $request['serial_no'],
                ]);
            }

            $purchase = Purchase::create([
                'serial_no' => $request['serial_no'],
                'vendor_id' => $request['vendor_id'],
                'date_time' => $request['date_time'],
                'user_id' => auth()->guard('api')->user()->id,
                'description' => $request['description'],
                'currency_id' => $request['currency_id'],
            ]);
            $totalmony = 0;
            foreach ($request->item as $valueItem) {
                // return ExchangeRate::latest()->first()->id;
                StockRecord::create([
                    'type' => "purchase",
                    'type_id' => $purchase->id,
                    'source' => $request->source,
                    'source_id' => $request['source_id']['id'],
                    'item_id' => $valueItem['item_id']['id'],
                    'increment' => $valueItem['increment'],
                    'decrement' => 0,
                    'uom_id' => $valueItem['item_id']['measurment_unites_min']['id'],
                    'increment_equiv' => $valueItem['increment_equiv'],
                    'decrement_equiv' => 0,
                    'uom_equiv_id' => $valueItem['item_id']['measurment_unites_sub']['id'],
                    'density' => $valueItem['density'],
                    'operation_id' => $valueItem['operation_id']['id'],
                    'unit_price' => $valueItem['unit_price'],
                    'total_price' => $valueItem['total_price'],
                    'remark' => $request['description'],
                    'ex_rate_id' => ExchangeRate::latest()->first()->id,

                ]);
                $totalmony = $totalmony + $valueItem['total_price'];
            }
            $totalmony = $totalmony + $request->total_price;
            // Create opening FR for the created Projet
            $data = [
                'type' => 'purchase',
                'type_id' => $purchase->id,
                'account_id' => $request['account_id'],
                'description' => $request['description'],
                'currency_id' => $request['currency_id'],
                'credit' => $totalmony,
                'debit' => 0,
                'ex_rate_id' => $request['currency_id'],
                'status' => 'INC'

            ];
            FinancialRecord::create($data);

            $account_id = 1;
            if ($request['currency_id'] == 2) {
                $account_id = config('app.cash_in_hand_usd');
            } else {
                $account_id = config('app.cash_in_hand_afn');
            }
            $datacasinhand = [
                'type' => 'purchase',
                'type_id' => $purchase->id,
                'account_id' => $account_id,
                'description' => $request['description'],
                'currency_id' => $request['currency_id'],
                'credit' => 0,
                'debit' => $totalmony,
                'ex_rate_id' => $request['currency_id'],
                'status' => 'EXP'

            ];
            FinancialRecord::create($datacasinhand);

            //create nofifications
            $notif_data = [
                'title' => 'خریداری جدید',
                'text' => 'یک خریداری جدید از ' . $request['vendor_name'] . ' به منبع ' . $request['source_id']['name'] . ' در سیستم ثبت گردید.',
                'type' => 'success',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'view',
                'url' => '/procurment',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '',
                'notif_source' => '',
                'notif_source_id' => '',
            ];
            $id = $purchase->id;
            $currency_id = FinancialRecord::where('type_id', $id)->where('type', 'purchase')->first('currency_id');
            $dataCashInHand = [
                'type' => 'purchase',
                'type_id' => $id,
                'account_id' => 34,
                'description' => 'قیمت مصارف انتقال',
                'currency_id' => $currency_id['currency_id'],
                'credit' => 0,
                'debit' => $request->service_cost,
                'ex_rate_id' => $currency_id['currency_id'],
                'status' => 'EXP'
    
            ];
            FinancialRecord::create($dataCashInHand);
    
            DB::commit();
            return ['msg' => 'purchase successfully inserted', $purchase];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Purchase::with([
            'vendor', 'user',
            'stock.item_id',
            'stock.uom_equiv_id',
            'stock.uom_id',
            'stock.operation_id',
        ])->find($id);
        $data['fr'] = FinancialRecord::where('type', "purchase")
            ->where('type_id', $id)->where('debit', 0)->first();
        $data['fr_service'] = FinancialRecord::where('type_id', $id)->where('type', 'purchase')->where('account_id', 34)->first();
        if($data['fr_service']){
            $data['fr_service'] = $data['fr_service']['debit'];
        }else{
            $data['fr_service'] = 0;
        }
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::with([
            'vendor', 'user',
        ])->find($id);
        $purchase['items'] = StockRecord::with([
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
            'operation_id'
        ])
            ->where('type', 'purchase')->where('type_id', $id)->get();
        foreach ($purchase['items'] as $item) {
            $purchase['source_type'] = $item['source'];
            $purchase['source'] = $item['source_id'];
            $purchase['source_id'] = $item['source_id'];
        }
        if ($purchase->source_type == "FDSP") {
            $purchase->source_id = Fuel_despenser::find($purchase->source_id);
        } else
        if ($purchase->source_type == "FSTR") {
            $purchase->source_id = Fuel_station_storage::find($purchase->source_id);
        } else
        if ($purchase->source_type == "STOK") {
            $purchase->source_id = Inventory::find($purchase->source_id);
        } else
        if ($purchase->source_type == "STRG") {
            $purchase->source_id = Storage::find($purchase->source_id);
        }

        return $purchase;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request['user_id'] = auth()->guard('api')->user()->id;
            $purchase = Purchase::find($id);
            $purchase->update($request->all());
            $totalmony = 0;
            return Purchase::find($id);
            StockRecord::where('type', "purchase")
                ->where('type_id', $id)->delete();
            foreach ($request->item as $valueItem) {
                $data = [
                    'type' => "purchase",
                    'type_id' => $purchase->id,
                    'source' => $request->source,
                    'source_id' => $request['source_id']['id'],
                    'item_id' => $valueItem['item_id']['id'],
                    'increment' => $valueItem['increment'],
                    'decrement' => 0,
                    'uom_id' => $valueItem['item_id']['uom_id']['id'],
                    'increment_equiv' => $valueItem['increment_equiv'],
                    'decrement_equiv' => 0,
                    'uom_equiv_id' => $valueItem['item_id']['uom_equiv_id']['id'],
                    'density' => $valueItem['density'],
                    'operation_id' => $valueItem['operation_id']['id'],
                    'unit_price' => $valueItem['unit_price'],
                    'total_price' => $valueItem['total_price'],
                    'remark' => $request['description'],
                    'ex_rate_id' => ExchangeRate::latest()->first()->id,
                ];
                StockRecord::create($data);
                $totalmony = $totalmony + $valueItem['total_price'];
            }

            // Remove unvalid data.
            FinancialRecord::where('type', "purchase")
                ->where('type_id', $id)->delete();

            // Create opening FR for the created Projet
            $dataDebitProj = [
                'type' => 'purchase',
                'type_id' => $purchase->id,
                'account_id' => $request['account_id'],
                'description' => $request['description'],
                'currency_id' => $request['currency_id'],
                'credit' => $totalmony,
                'debit' => 0,
                'ex_rate_id' => $request['currency_id'],
                'status' => 'INC'

            ];
            FinancialRecord::create($dataDebitProj);

            if ($request['currency_id'] == 2) {
                $account_id = config('app.cash_in_hand_usd');
            } else {
                $account_id = config('app.cash_in_hand_afn');
            }
            $dataCashInHand = [
                'type' => 'purchase',
                'type_id' => $purchase->id,
                'account_id' => $account_id,
                'description' => $request['description'],
                'currency_id' => $request['currency_id'],
                'credit' => 0,
                'debit' => $totalmony,
                'ex_rate_id' => $request['currency_id'],
                'status' => 'EXP'

            ];
            FinancialRecord::create($dataCashInHand);
            DB::commit();
            return ['msg' => 'purchase successfully updated', $purchase];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::findOrFail($id);
            StockRecord::where('type', "purchase")->where('type_id', $purchase->id)->delete();
            FinancialRecord::where('type', "purchase")->where('type_id', $purchase->id)->delete();
            $purchase->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function purchaseServiceCost(Request $request, $id)
    {
        $fr = FinancialRecord::where('type', 'purchase')->
        where('type_id', $id)->where('account_id', 34)->first();
        $fr_credit = FinancialRecord::where('type', 'purchase')->
        where('type_id', $id)->where('account_id','!=' ,34)->where('debit', 0)->first();
        $fr_debit = FinancialRecord::where('type', 'purchase')->
        where('type_id', $id)->where('account_id','!=' ,34)->where('credit', 0)->first();
        if($fr){
            $fr->update(['debit' => $request->service_cost]);
        }
        if($fr_credit){
            $fr_credit->update(['credit' => $request->total_price]);
        }
        if($fr_debit){
            $fr_debit->update(['debit' => $request->total_price]);
        }
    }
    public function changeStep($id, $stepNo)
    {
        // return response(['id'=>$id,'stid'=>$stepNo]);
        $purchase = Purchase::findOrFail($id);
        $purchase->step = $stepNo;
        $purchase->save();
    }
}
