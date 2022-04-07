<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\SerialNumber;
use App\Models\FinancialRecord;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function serial()
    {
        $serial_number = SerialNumber::where('type', 'TRA')->latest()->first();
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
        //return Transaction::with('user','currency')->get();
        return Transaction::with('user', 'currency')->get();
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

            $serial_number = SerialNumber::where('type',   'TRA')->latest()->first();
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
                    'type' => 'TRA',
                    'prefix' => 'TRA',
                    'value' => $request['serial_no'],
                ]);
            }
            $transaction = Transaction::create([
                'serial_no' => $request['serial_no'],
                'currency_id' => $request['currency_id'],
                'datetime' => $request['datetime'],
                'title' => $request['title'],
                'transaction_status' => $request['transaction_status'],
                'ammount' => $request['ammount'],
                'description' => $request['description'],
                'credit_desc' => $request['credit_desc'],
                'credit_desc' => $request['credit_desc'],
                'user_id' => auth()->guard('api')->user()->id,
            ]);
            // Create opening FR for the created Projet
            $data = [
                'type' => 'TRA',
                'type_id' => $transaction->id,
                'account_id' => $request['credit_account']['id'],
                'description' => $request['credit_desc'],
                'currency_id' => $request['currency_id'],
                'credit' => $request['ammount'],
                'debit' => 0,
                'ex_rate_id' => ExchangeRate::where('currency_id', $request['currency_id'])->latest()->first()->id,
                'status' => 'INC'

            ];
            $financialRecord1 = FinancialRecord::create($data);

            // Create opening FR for the created Projet
            $data = [
                'type' => 'TRA',
                'type_id' => $transaction->id,
                'account_id' => $request['debit_account']['id'],
                'description' => $request['debit_desc'],
                'currency_id' => $request['currency_id'],
                'credit' => 0,
                'debit' => $request['ammount'],
                'ex_rate_id' => ExchangeRate::where('currency_id', $request['currency_id'])->latest()->first()->id,
                'status' => 'EXP'

            ];
            $financialRecord2 = FinancialRecord::create($data);


            DB::commit();
            return ['msg' => 'purchase successfully inserted', [$transaction, $financialRecord1, $financialRecord2]];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with('user', 'currency')->find($id);
        $creditFinancialRecord = FinancialRecord::with('account')->where('type_id', $id)->where('credit', '>', 0)->where('type', 'TRA')->first();
        $transaction['fr'] = $creditFinancialRecord;
        $transaction['credit_account'] = $creditFinancialRecord->account;
        $debitFinancialRecord = FinancialRecord::with('account')->where('type_id', $id)->where('debit', '>', 0)->where('type', 'TRA')->first();
        $transaction['debit_account'] = $debitFinancialRecord->account;
        return $transaction;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $transaction = Transaction::find($id);
            $transaction->update($request->all());

            $creditFinancialRecord = FinancialRecord::where('type_id', $id)->where('credit', '>', 0)->where('type', 'TRA')->first();
            $data = [
                'account_id' => $request['credit_account']['id'],
                'description' => $request['credit_desc'],
                'currency_id' => $request['currency_id'],
                'credit' => $request['ammount'],
                'ex_rate_id' => $request['currency_id'],
            ];
            $creditFinancialRecord->update($data);

            $debitFinancialRecord = FinancialRecord::where('type_id', $id)->where('debit', '>', 0)->where('type', 'TRA')->first();
            $data = [
                'account_id' => $request['debit_account']['id'],
                'description' => $request['debit_desc'],
                'currency_id' => $request['currency_id'],
                'debit' => $request['ammount'],
                'ex_rate_id' => $request['currency_id'],
            ];
            $debitFinancialRecord->update($data);

            DB::commit();
            return ['msg' => 'tran$transactions successfully inserted', $transaction];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transactions = Transaction::findOrFail($id);
            $transactions->delete();
            $finanrecord = FinancialRecord::where('type_id', $id)->where('type', 'TRA')->get();
            if ($finanrecord) {
                foreach ($finanrecord as $val) {
                    $record = FinancialRecord::findOrFail($val->id);
                    $record->delete();
                }
            }

            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function transactionConfirm(Request $request, $id)
    {
        $transactions = Transaction::findOrFail($id);
        if ($request->step == 3) {
            $transactions->is_approved = $request->is_approved;
        } else {
            $transactions->step = 1;
        }
        $transactions->save();
        DB::table('financial_records')->where('type_id', $id)
        ->where('type', 'TRA')->update([
            'valid' => $request->is_approved,
        ]);
    }

}
