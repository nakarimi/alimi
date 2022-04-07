<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

use Carbon\Carbon;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::currency_notif();
        // auth()->guard('api')->user()->id
        $ndata = Notification::with(['user_notification' => function ($q) {
            $q->where('user_id', '=', auth()->guard('api')->user()->id);
        }])
            ->has('user_notification')
            ->get()
            ->sortByDesc('gen_date')
            ->toArray();
        $x = array_values($ndata);
        return array_filter($x, fn ($value) => !is_null($value['user_notification']) && $value['user_notification'] !== '');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->type = "normal";
        $notification->save();
        return response(['status' => 'changed']);
    }
}
