<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\AccountType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts =  AccountType::with('accounts.financial_records')->get();
        return $accounts;
    }
    public function accountType()
    {
        $accounts =  AccountType::with('type_id')->get();
        return $accounts;
        // if(auth()->guard('api')->user()->hasPermissionTo(81))
        // {
        // }
        // else{
        //     return response('Permission Denied!',403);
        // }

    }
    public function allAccounts()
    {
        $accounts_types = AccountType::with('accounts.financial_records.exchange_rate')
            // ->has('accounts')
            // ->has('accounts.financial_records')
            // ->has('accounts.financial_records.exchange_rate')
            ->get();
        foreach ($accounts_types as $key1 => &$type) {
            $this->loadType($type);
            foreach ($type['accounts'] as $key2 => &$acc) {
                $total_af = 0;
                $total_usd = 0;
                Helper::calc_account_balance($acc['financial_records'], $total_af, $total_usd);
                $accounts_types[$key1]['accounts'][$key2]['afn'] = $total_af;
                $accounts_types[$key1]['accounts'][$key2]['usd'] = $total_usd;
            }
        }
        return $accounts_types;
    }

    public function calc($account)
    {
        foreach ($account['accounts'] as $key => $acc) {
            foreach ($acc as $key => $a) {
                return $a;
                return $a['financial_records'];
            }
        }
    }
    public function loadType($account)
    {
        if ($account['type_id'] != null) {
            $account['type_id'] = AccountType::where('id', $account['type_id'])->first();
            $this->loadType($account['type_id']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            // 'type_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $acountType = AccountType::create([
                'title' => $request['title'],
                'type_id' => $request['type_id']['id'],
                'system' => 0,
            ]);
            DB::commit();
            return $acountType;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function show(AccountType $accountType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountType $accountType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2',
        ]);
        DB::beginTransaction();
        try {
            $acountType = AccountType::findOrFail($id);
            $request['type_id'] = ($request['type_id']) ? $request['type_id']['id'] : null;
            $acountType->update($request->all());
            DB::commit();
            return $acountType;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function destroy($accountTypeID)
    {
        DB::beginTransaction();
        try {
            $acountType = AccountType::findOrFail($accountTypeID);
            $acountType->delete();

            DB::commit();
            return Response::json($acountType, 201);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
