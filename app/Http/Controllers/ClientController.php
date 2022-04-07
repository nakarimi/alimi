<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

use App\Models\Client;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::with('pro_date')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        DB::beginTransaction();
        try {

            $this->validate($request, [
                'name' => 'required|min:2',
                'email' => 'required|email',
                'phone' => 'required',
                // 'website' => 'required',
                'address' => 'required',
                'logo' => 'required'
            ]);
            $account = Account::create([
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => 2,
                'name' => $request['name'],
                'ref_code' => 'client',
                'status' => 1,
                'description' => $request['name'],
                'system' => 1
            ]);
            // upload the logo
            $photoname = time() . '.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
            \Image::make($request->logo)->save(public_path('images/img/clients/') . $photoname);
            $request->merge(['logo' => $photoname]);
            // find the last ID of account

            $client = Client::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'website' => $request['website'],
                'address' => $request['address'],
                'logo' => $photoname,
                'account_id' => $account->id
            ]);
            DB::commit();
            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Client::where('id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required',
            'website' => 'required',
            'address' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $client = Client::findOrFail($client->id);
            if (!($client->logo == $request->logo)) {
                if (file_exists(public_path('images/img/clients/') . $client->logo)) {
                    // unlink(public_path('images/img/clients/') . $client->logo);
                }
                $photoname = time() . '.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
                \Image::make($request->logo)->save(public_path('images/img/clients/') . $photoname);
                $request->merge(['logo' => $photoname]);

                $client->logo = $photoname;
            }

            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->website = $request->website;
            $client->address = $request->address;
            $client->account_id = $request->account_id;
            $client->save();
            DB::commit();
            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        DB::beginTransaction();
        try {
            $client = Client::findOrFail($client->id);
            if ($client->logo != 0) {
                if (file_exists(public_path('images/img/clients/') . $client->logo)) {
                    unlink(public_path('images/img/clients/') . $client->logo);
                }
            }
            $client->delete();
            $relatedAccount = Account::Where('id', $client->account_id)->first();
            $relatedAccount->delete();

            DB::commit();
            return response('Succefully Deleted!', 201);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
