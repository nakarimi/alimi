<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Helper\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::with([
            'measurment_unites_min',
            'measurment_unites_sub',
            'type',
            'uom_equiv_id',
            'uom_id'
        ])->get();
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
            $item = Item::create([
                'type_id' => $request['type_id']['id'],
                'name' => $request['name'],
                'uom_id' => $request['uom_id']['id'],
                'uom_equiv_id' => $request['uom_equiv_id']['id'],
                'equivalent' => $request['equivalent'],
                'description' => $request['description'],
            ]);
            DB::commit();
            return $item;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($id);
            $item->update([
                'type_id' => $request['type_id']['id'],
                'name' => $request['name'],
                'uom_id' => $request['uom_id']['id'],
                'uom_equiv_id' => $request['uom_equiv_id']['id'],
                'equivalent' => $request['equivalent'],
                'description' => $request['description'],
            ]);
    
            DB::commit();
            return $item;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
