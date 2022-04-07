<?php

namespace App\Http\Controllers;
use App\Helper\Helper;

use App\Models\Account;
use App\Models\Storage;
use App\Models\Transfer;

use App\Models\Inventory;
use App\Models\StockRecord;
use App\Models\Notification;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;
use App\Models\Fuel_despenser;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Transfer::latest()->get();
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
            $serial_no = Helper::getSerialNo('transfer', 'transfer');
            $request['serial_no'] = $serial_no->value;

            // Default AFN currency ID.
            $request['currency_id'] = 1;
            $request['ammount'] = $request->total;
            $source = $request->source_id;
            $sourceABR = $request->source;
            // return Response::json($source, 400);
            $request['destination_id'] = $request['destination_id']['id'];
            $t = Transfer::create($request->all());
            $account = Account::find(config('app.base_transfer_account'));

            Helper::createDoubleFR('TRS', $t, $account, $request);

            $totalmoney = 0;
            $stocks = Helper::salesCreateStockRecords('TRS', $request->item, $t, $source, $request, $totalmoney, $sourceABR, $source['id']);
            $stocks = Helper::incrementCreateStockRecords('TRS', $request->item, $t, $request['destination_id'], $request, $totalmoney, $request['destination_type'], $request['destination_id']);

            $target = $request->destination;
            $source = $source['name'];
            $nofication = [
                'title' => 'انتقال جدید',
                'text' => 'یک انتقال جدید از ' . $source . ' به ' . $target . ' در سیستم ثبت گردید.',
                'type' => 'normal',
                'gen_date' => Carbon::now(),
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'view',
                'url' => 'sales?list',
                'user_id' => auth()->guard('api')->user()->id,
            ];
            $newNotif = Notification::create($nofication);
            DB::commit();
            return $t;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trs = Transfer::findOrFail($id);
        $trs['item'] = StockRecord::with([
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
            'operation_id'
        ])
            ->where('type', 'TRS')
            ->where('source', 'STRG')
            ->where('type_id', $id)->get();

        return $trs;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trs = Transfer::findOrFail($id);
        $trs['item'] = StockRecord::with([
            'item_id.uom_equiv_id',
            'item_id.uom_id',
            'item_id.type',
            'operation_id'
        ])
            ->where('type', 'TRS')
            ->where('decrement_equiv', '!=', "0")
            ->where('type_id', $id)->get();

            if ($trs->destination == "FDSP") {
                $trs->destination_id = Fuel_despenser::find($trs->destination_id);
            } else
            if ($trs->destination == "FSTR") {
                $trs->destination_id = Fuel_station_storage::find($trs->destination_id);
            } else
            if ($trs->destination == "STOK") {
                $trs->destination_id = Inventory::find($trs->destination_id);
            } else
            if ($trs->destination == "STRG") {
                $trs->destination_id = Storage::find($trs->destination_id);
            }
            $trs['source_id'] = ($trs->item[0]) ? $trs->item[0]->source_id : null;
            $trs['source_type'] = ($trs->item[0]) ? $trs->item[0]->source : null;

            if ($trs->source_type == "FDSP") {
                $trs->source_id = Fuel_despenser::find($trs->source_id);
            } else
            if ($trs->source_type == "FSTR") {
                $trs->source_id = Fuel_station_storage::find($trs->source_id);
            } else
            if ($trs->source_type == "STOK") {
                $trs->source_id = Inventory::find($trs->source_id);
            } else
            if ($trs->source_type == "STRG") {
                $trs->source_id = Storage::find($trs->source_id);
            }
    
        return $trs;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request['currency_id'] = 1;
            $request['ammount'] = $request->total;
            $source = $request->source_id;
            $sourceABR = $request->source;
            $request['destination_id'] = $request['destination_id']['id'];
            $transfer = Transfer::find($id); 
            $transfer->update($request->all());
            $account = Account::find(config('app.base_transfer_account'));

            StockRecord::where('type', 'TRS')
            ->where('type_id', $id)
            ->delete();
            FinancialRecord::where('type', 'TRS')
            ->where('type_id', $id)
            ->delete();

            Helper::createDoubleFR('TRS', $transfer, $account, $request);

            $totalmoney = 0;
            $stocks = Helper::salesCreateStockRecords('TRS', $request->item, $transfer, $source, $request, $totalmoney, $sourceABR, $source['id']);
            $stocks = Helper::incrementCreateStockRecords('TRS', $request->item, $transfer, $request['destination_id'], $request, $totalmoney, $request['destination_type'], $request['destination_id']);

            $target = $request->destination;
            $source = $source['name'];
            $nofication = [
                'title' => 'انتقال جدید',
                'text' => 'یک انتقال جدید از ' . $source . ' به ' . $target . ' در سیستم ثبت گردید.',
                'type' => 'normal',
                'gen_date' => Carbon::now(),
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'view',
                'url' => 'sales?list',
                'user_id' => auth()->guard('api')->user()->id,
            ];
            $newNotif = Notification::create($nofication);
            DB::commit();
            return $transfer;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Helper::deleteTransfer($id);
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    public function changeStep($id, $stepNo)
    {
        // return response(['id'=>$id,'stid'=>$stepNo]);
        $transfer = Transfer::findOrFail($id);
        $transfer->step = $stepNo;
        $transfer->save();
    }
}
