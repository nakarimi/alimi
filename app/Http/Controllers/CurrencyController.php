<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\Currency;
use App\Models\User;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Currency::with(['last_rate'])->get();
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
            'sign_en' => 'required|unique:currencies|min:2',
            'sign_fa' => 'required|unique:currencies|min:2',
        ]);
        if ($last = ExchangeRate::latest()->first()) {
            $lastCounter = $last->counter + 1;
        } else {
            $lastCounter = 1;
        }

        if ($new = Currency::create($request->all())) {
            $rate = [
                'currency_id' => $new->id,
                'user_id' => auth()->guard('api')->user()->id,
                'rate' => $request->rate,
                'counter' => $lastCounter
            ];
            if ($ex = ExchangeRate::create($rate)) {
                return $ex;
            } else {
                Currency::find($new->id)->delete();
                return $ex;
            }
        } else {
            return $new;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $this->validate($request, [
            'sign_en' => 'required|min:2',
            'sign_fa' => 'required|min:2',
        ]);
        if ($currency->update($request->all())) {
            return $currency;
        } else {
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
    }

    public function rates(Request $request)
    {
        DB::beginTransaction();
        try {

            $getcounter = ExchangeRate::latest()->first();
            $newcounter = $getcounter->counter + 1;
            foreach ($request['currencies'] as $key => $value) {
                // return $value['id'];
                $rate = [
                    'currency_id' => $value['id'],
                    'user_id' => auth()->guard('api')->user()->id,
                    'rate' => $value['last_rate']['rate'],
                    'system_rate' => 1 / $value['last_rate']['rate'],
                    'counter' => $newcounter,
                ];
                // return $rate;
                ExchangeRate::create($rate);
            }

            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
