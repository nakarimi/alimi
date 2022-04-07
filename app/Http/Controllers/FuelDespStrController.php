<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;

use App\Models\Fuel_desp_str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class FuelDespStrController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\fuel_desp_str  $fuel_desp_str
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $fuel_desp_str = fuel_desp_str::findOrFail($id);
            $fuel_desp_str->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
