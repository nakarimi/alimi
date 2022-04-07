<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

use App\Models\Storage;
use App\Models\Fuel_station;
use Illuminate\Http\Request;
use App\Models\Fuel_desp_str;
use App\Models\Fuel_despenser;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class FuelDespenserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Fuel_despenser::with(['fuel_station'])->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stations = Fuel_despenser::where('station_id', $request->station_id)->get();
        foreach ($stations as $station) {
            if ($station->name == $request['name']) {
                return response(['status' => 'same']);
            }
        }
        $despenser = Fuel_despenser::create($request->all());
        foreach ($request->storage_id as $key => $storage) {
            Fuel_desp_str::create(['storage_id' => $storage['id'], 'despencer_id' => $despenser->id]);
        }
        return $despenser;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fuel_despenser  $fuel_despenser
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Fuel_despenser::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fuel_despenser  $fuel_despenser
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fDesp = Fuel_despenser::with('fuel_station')->find($id);
        $fDespStr = Fuel_desp_str::where('despencer_id', $id)->pluck('storage_id')->all();
        $fDesp['storage_id'] = Fuel_station_storage::whereIn('id', $fDespStr)->get();
        return $fDesp;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fuel_despenser  $fuel_despenser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $stations = Fuel_despenser::where('station_id', $request->station_id)
                ->where('id', '!=', $id)
                ->get();
            foreach ($stations as $station) {
                if ($station->name == $request['name']) {
                    return response(['status' => 'same']);
                }
            }
            $despenser = Fuel_despenser::find($id);
            $despenser->update(['name' => $request['name']]);
            $validId = [];
            foreach ($request->storage_id as $key => $storage) {
                $exist = Fuel_desp_str::where('despencer_id', $id)->where('storage_id', $storage['id'])->get();
                if (count($exist->toArray()) == 0) {
                    $exist = Fuel_desp_str::create(['storage_id' => $storage['id'], 'despencer_id' => $despenser->id]);
                }
                if (count($exist->toArray()) != 0) {
                    array_push($validId, ($exist[0]) ? $exist[0]['id'] : $exist['id']);
                }
            }
            Fuel_desp_str::where('despencer_id', $id)->whereNotIn('id', $validId)->delete();
            DB::commit();
            return [$despenser, $validId];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fuel_despenser  $fuel_despenser
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Fuel_desp_str::where('despencer_id', $id)->delete();
            $fuel_despenser = Fuel_despenser::findOrFail($id);
            $fuel_despenser->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
