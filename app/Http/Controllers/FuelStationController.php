<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

use App\Models\Fuel_station;

use Illuminate\Http\Request;
use App\Models\Fuel_despenser;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class FuelStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Fuel_station::all();
    }
    public function latest()
    {
        return Fuel_station::first();
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
            $fuel_station = Fuel_station::create([
                'name' => $request['name'],
                'manager' => $request['manager'],
                'phone' => $request['phone'],
                'address' => $request['address']
            ]);
            DB::commit();
            return $fuel_station;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fuel_station  $fuel_station
     * @return \Illuminate\Http\Response
     */
    public function show(Fuel_station $fuel_station)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fuel_station  $fuel_station
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Fuel_station::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fuel_station  $fuel_station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fuel_station = Fuel_station::find($id);
        DB::beginTransaction();
        try {
            $fuel_station->update([
                'name' => $request['name'],
                'manager' => $request['manager'],
                'phone' => $request['phone'],
                'address' => $request['address']
            ]);
            DB::commit();
            return $fuel_station;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fuel_station  $fuel_station
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Fuel_station_storage::where('station_id', $id)->delete();
            Fuel_despenser::where('station_id', $id)->delete();

            $fuel_station = Fuel_station::findOrFail($id);
            $fuel_station->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
