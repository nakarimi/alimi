<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

use App\Models\Account;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\FinancialRecord;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getaccount()
    {
        return Account::all();
    }

    public function index()
    {
        return Account::with('financial_records')->get();
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

            $this->validate($request, [
                'type_id' => 'required',
                'ref_code' => 'required',
                'name' => 'required',
            ]);
            if (gettype($request->type_id) != 'integer') {
                $request->type_id = $request['type_id']['id'];
            }

            $data = [
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'ref_code' => $request->ref_code,
                'status' => $request->status,
                'description' => $request->description,
                // 'system' => $request->system,    
            ];
            if ($request->debit > 0) {
                $request->credit = null;
            }
            $newAccount = Account::create($data);
            $data = [
                'type' => 'account',
                'type_id' => $request->type_id,
                'account_id' => $newAccount->id,
                'description' => 'Dynamically Created From Account.',
                'currency_id' => config('app.currency_afn'),
                'credit' => $request->credit,
                'debit' => $request->debit,
                'ex_rate_id' => ExchangeRate::latest()->first()->id,
                'status' => 'opn'
            ];
            FinancialRecord::create($data);
            DB::commit();
            return $newAccount;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $account = Account::with('financial_records')->find($account->id);
        $acc_type = Account::with('type')->first();
        $account['account_type'] = $acc_type['type'];
        return $account;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'type_id' => 'required',
                'ref_code' => 'required',
                'name' => 'required',
            ]);
            if (gettype($request->type_id) != 'integer') {
                $request->type_id = $request['type_id']['id'];
            }

            $data = [
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'ref_code' => $request->ref_code,
                'status' => $request->status,
                'description' => $request->description,
                // 'system' => $request->system,    
            ];
            if ($request->debit > 0) {
                $request->credit = null;
            }
            $account = Account::find($id);
            $account->update($data);
            DB::commit();
            return $account;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        DB::beginTransaction();
        try {
            FinancialRecord::where('account_id', $account->id)->delete();
            $account->delete();
            DB::commit();
            return response(['status' => 'success'], 204);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function bankAccounts()
    {
        return Account::with('type')->whereIn('type_id', config('app.pro_bank_accounts'))->get();
    }
}
