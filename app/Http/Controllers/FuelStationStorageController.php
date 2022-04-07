<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;

use App\Models\Fuel_desp_str;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class FuelStationStorageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Fuel_station_storage::with('fuel_station')->with('measur_unit')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return Fuel_station_storage::create([
            'station_id' => $request['station_id'],
            'name' => $request['name'],
            'supervisor' => $request['supervisor'],
            'capacity' => $request['capacity'],
            'oum_id' => $request['oum_id']['id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\fuel_station_storage  $fuel_station_storage
     * @return \Illuminate\Http\Response
     */
    public function show(fuel_station_storage $fuel_station_storage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\fuel_station_storage  $fuel_station_storage
     * @return \Illuminate\Http\Response
     */
    public function edit(fuel_station_storage $fuel_station_storage)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\fuel_station_storage  $fuel_station_storage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $toUpdate = Fuel_station_storage::findOrFail($id);
        $toUpdate->update([
            'station_id' => $request['station_id'],
            'name' => $request['name'],
            'supervisor' => $request['supervisor'],
            'capacity' => $request['capacity'],
            'oum_id' => $request['oum_id']['id'],
        ]);
        return $toUpdate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\fuel_station_storage  $fuel_station_storage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Fuel_desp_str::where('storage_id', $id)->delete();
            $storage = Fuel_station_storage::findOrFail($id);
            $storage->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function bystation(Request $request, fuel_station_storage $fuel_station_storage)
    {
        return Fuel_station_storage::where('station_id', $request->id)->get();
    }
}
