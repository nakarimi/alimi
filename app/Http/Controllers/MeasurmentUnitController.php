<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;

use App\Models\MeasurmentUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MeasurmentUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MeasurmentUnit::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return MeasurmentUnit::create([
            'title' => $request['title'],
            'acronym' => $request['acronym'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeasurmentUnit  $measurmentUnit
     * @return \Illuminate\Http\Response
     */
    public function show(MeasurmentUnit $measurmentUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeasurmentUnit  $measurmentUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(MeasurmentUnit $measurmentUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurmentUnit  $measurmentUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $MU = MeasurmentUnit::find($id);
        $MU->update([
            'title' => $request['title'],
            'acronym' => $request['acronym'],
        ]);
        return $MU;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeasurmentUnit  $measurmentUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $uom = MeasurmentUnit::findOrFail($id);
            $result = $uom->delete();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
