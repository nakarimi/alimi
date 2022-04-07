<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Helper\Helper;

use App\Models\Vendor;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vendor::all();
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
                'name' => 'required|min:2',
                'phone' => 'required',
                'address' => 'required',
            ]);
            Account::create([
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => 9,
                'name' => $request['name'],
                'ref_code' => 34232,
                'status' => 1,
                'description' => $request['address'],
                'system' => 0,
            ]);

            $account =  Account::latest()->first();

            Vendor::create([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'account_id' => $account->id,
            ]);

            DB::commit();
            return ['msg' => 'vendor succesfully inserted'];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Vendor::where('id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $vendor = Vendor::findOrFail($vendor->id);
        $vendor->name = $request->name;
        $vendor->phone = $request->phone;
        $vendor->address = $request->address;
        $vendor->account_id = $request->account_id;
        $vendor->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        DB::beginTransaction();
        try {
            $vendor = Vendor::findOrFail($vendor->id);
            $relatedAccount = Account::Where('id', $vendor->account_id)->first();
            $relatedAccount->delete();
            $vendor->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
