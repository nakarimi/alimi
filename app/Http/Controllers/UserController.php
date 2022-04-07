<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

use App\Models\User;

use Illuminate\Http\Request;
use App\Models\UserAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{

    public function user(Request $request)
    {
        $user = $request->user();
        $user['permission_keys'] = $user->permissions->pluck('name')->toArray();
        return $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::get();
        return User::where('id', '!=', auth()->guard('api')->user()->id)->get();
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
            'firstName' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        DB::beginTransaction();
        try {
            $photoname = NULL;
            if ($request->image != null) {

                $photoname = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                \Image::make($request->image)->save(public_path('img/user/') . $photoname);
                $request->merge(['photo' => $photoname]);
            }
            $user = User::create([
                'firstName' => $request['firstName'],
                'lastName' => $request['lastName'],
                'user_type' => $request['user_type']['value'],
                'position' => $request['position'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'password' => Hash::make($request['password']),
                'image' => $photoname,
            ]);

            if ($request->userleaders != null) {
                foreach ($request->userleaders as $key => $val) {
                    UserAssignment::create(['lead_user_id' => $val['id'], 'user_id' => $user->id]);
                }
            }

            $userPrivilegesId = array_column($request->privileges, 'id');
            $systemPrivileges = Permission::where('p_id', null)->get();
            foreach ($systemPrivileges as $key => &$sPri) {
                if (in_array($sPri->id, $userPrivilegesId)) {
                    $user->givePermissionTo($sPri->id);
                    foreach ($request->privileges as $key => $privilage) {
                        if ($privilage['id'] == $sPri->id) {
                            foreach ($privilage['can_do'] as $key => $can) {
                                if (isset($can['assign']) && $can['assign'] == true) {
                                    $user->givePermissionTo($can['id']);
                                }
                            }
                            break;
                        }
                    }
                }
            }
            DB::commit();
            return ['msg' => 'user successfully inserted', $systemPrivileges];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::findOrFail($id);
        $user['userleaders'] = UserAssignment::where('user_id', $id)->get()->toArray();
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $photoname = NULL;
            if ($request->image != null) {
                if (strpos($request->image, 'base64')) {
                    $photoname = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                    \Image::make($request->image)->save(public_path('img/user/') . $photoname);
                    $request->merge(['photo' => $photoname]);
                } else {
                    $photoname = $request->image;
                }
            }

            $user = User::findOrFail($id);
            $request['user_type'] = $request->user_type['value'];
            if($request['password']){
                $request['password'] = Hash::make($request['password']);
            }else{
                unset($request['password']);
            }
            $request['image'] =  ($photoname) ? $photoname : '';
            $user->update($request->all());

            if ($request->userleaders != null) {
                UserAssignment::where('user_id', $user->id)->delete();
                foreach ($request->userleaders as $key => $val) {
                    UserAssignment::create(['lead_user_id' => $val['id'], 'user_id' => $user->id]);
                }
            }
            DB::commit();
            return ["message" => "موفقانه معلومات اصلاح شد"];
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->syncPermissions();
            $user->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
