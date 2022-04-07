<?php

namespace App\Helper;

use App\Models;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use App\Models\SaleOne;
use App\Models\SaleTwo;
use App\Models\Proposal;
use App\Models\SaleFour;
use App\Models\Transfer;
use App\Models\SaleThree;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use App\Models\Notification;
use App\Models\SerialNumber;
use App\Models\FinancialRecord;
use App\Models\Project;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PHPUnit\TextUI\Help;

class Helper
{
    public static function getInformation()
    {
        return "getInformation()";
    }
    public static function getSerialNo($type, $prefix)
    {
        $serial_no = SerialNumber::where('type', $type)->first();
        if (!empty($serial_no)) {
            $serial_no->update(['value' => $serial_no->value + 1]);
        } else {
            $serial_no = SerialNumber::create(['type' => $type, 'prefix' => $prefix, 'value' => 101]);
        }
        return $serial_no;
    }
    public static function createUserAssign($notif_id, $status = "nor")
    {
        $user = User::all();
        foreach ($user as $value) {
            if ($value->user_type == 1 || $value->user_type == 2) {
                UserNotification::create([
                    'user_id' => $value->id,
                    'notification_id' => $notif_id,
                    'status' => $status,
                    'pin' => 0,
                    'done' => 0,
                ]);
            }
        }
    }
    public static function createDoubleFR($type, $newEntity, $newAcc, $request)
    {
        // Create opening FR for the created Sales
        $FRData = [
            'type' => $type, // here the type of financial record is project
            'type_id' => $newEntity->id, //Project Id will be used here as type id
            'account_id' => $newAcc->id,
            'description' => 'عملیه مالی ثبت شده برای - ' . $newEntity->id,
            'currency_id' => $request->currency_id,
            'credit' => 0,
            'debit' => $request->total,
            'ex_rate_id' => ExchangeRate::latest()->first()->id,
            'status' => 'OPN'
        ];
        $newFR = FinancialRecord::create($FRData);

        // Create opening FR for the created Sales
        if ($request->bank_account) {
            $bA_id = $request->bank_account;
        } else {
            $bA_id = ($request->currency_id == 1) ? config('app.cash_in_hand_afn') : config('app.cash_in_hand_usd');
        }
        $FRdestinationAccount = [
            'type' => $type, // here the type of financial record is project
            'type_id' => $newEntity->id, //Project Id will be used here as type id
            'account_id' => $bA_id,
            'description' => 'عملیه مالی ثبت شده برای - ' . $newEntity->id,
            'currency_id' => $request->currency_id,
            'credit' => $request->total,
            'debit' => 0,
            'ex_rate_id' => ExchangeRate::latest()->first()->id,
            'status' => 'INC'
        ];
        $newFRCashInHand = FinancialRecord::create($FRdestinationAccount);
        return [$newFR, $newFRCashInHand];
    }

    public static function salesCreateStockRecords($type, $items, $newSale, $storage, $request, &$totalmoney, $source, $source_id)
    {
        foreach ($items as $valueItem) {

            $stdata = [
                'type' => $type,
                'type_id' => $newSale->id,
                'source' => $source,
                'source_id' => $source_id,
                'item_id' => $valueItem['item_id']['id'],
                'decrement' => (array_key_exists("ammount", $valueItem) && $valueItem['ammount']) ? $valueItem['ammount'] : $valueItem['increment'],
                'increment' => 0,
                'uom_id' => (array_key_exists('measurment_unites_min', $valueItem['item_id'])) ? $valueItem['item_id']['measurment_unites_min']['id'] : $valueItem['item_id']['uom_id']['id'],
                'decrement_equiv' => (array_key_exists("equivalent", $valueItem) && $valueItem['equivalent']) ? $valueItem['equivalent'] : $valueItem['increment_equiv'],
                'increment_equiv' => 0,
                'uom_equiv_id' => array_key_exists('measurment_unites_sub', $valueItem['item_id']) ? $valueItem['item_id']['measurment_unites_sub']['id'] : (in_array(gettype($valueItem['item_id']['uom_equiv_id']), ['object', 'array']) ? $valueItem['item_id']['uom_equiv_id']['id'] : $valueItem['item_id']['uom_equiv_id']),
                'density' => array_key_exists('density', $valueItem) ? $valueItem['density'] : null,
                'operation_id' => $valueItem['operation_id']['id'],
                'unit_price' => (array_key_exists("unit_price", $valueItem) && $valueItem['unit_price'] != null) ? $valueItem['unit_price'] : 0,
                'total_price' => (array_key_exists("total_price", $valueItem) && $valueItem['total_price'] != null) ? $valueItem['total_price'] : 0,
                'remark' => $request['description'],
                'ex_rate_id' => ExchangeRate::latest()->first()->id,
            ];
            $stocks[] = StockRecord::create($stdata);
            $totalmoney = $totalmoney + $valueItem['total_price'];
        }
        return $stocks;
    }
    public static function incrementCreateStockRecords($type, $items, $newSale, $storage, $request, &$totalmoney, $source, $source_id)
    {
        foreach ($items as $valueItem) {

            $stocks[] = StockRecord::create([
                'type' => $type,
                'type_id' => $newSale->id,
                'source' => $source,
                'source_id' => $source_id,
                'item_id' => $valueItem['item_id']['id'],
                'increment' => (array_key_exists("ammount", $valueItem) && $valueItem['ammount']) ? $valueItem['ammount'] : $valueItem['increment'],
                'decrement' => 0,
                'uom_id' => (array_key_exists('measurment_unites_min', $valueItem['item_id'])) ? $valueItem['item_id']['measurment_unites_min']['id'] : $valueItem['item_id']['uom_id']['id'],
                'increment_equiv' => (array_key_exists("equivalent", $valueItem) && $valueItem['equivalent']) ? $valueItem['equivalent'] : $valueItem['increment_equiv'],
                'deccrement_equiv' => 0,
                'uom_equiv_id' => array_key_exists('measurment_unites_sub', $valueItem['item_id']) ? $valueItem['item_id']['measurment_unites_sub']['id'] : (in_array(gettype($valueItem['item_id']['uom_equiv_id']), ['object', 'array']) ? $valueItem['item_id']['uom_equiv_id']['id'] : $valueItem['item_id']['uom_equiv_id']),
                // 'uom_equiv_id' => array_key_exists('measurment_unites_sub', $valueItem['item_id']) ? $valueItem['item_id']['measurment_unites_sub']['id'] : (gettype($valueItem['item_id']['uom_equiv_id']) != 'object' ? $valueItem['item_id']['uom_equiv_id'] : $valueItem['item_id']['uom_equiv_id']['id']),
                'density' => array_key_exists('density', $valueItem) ? $valueItem['density'] : null,
                'operation_id' => $valueItem['operation_id']['id'],
                'unit_price' => (array_key_exists("unit_price", $valueItem) && $valueItem['unit_price'] != null) ? $valueItem['unit_price'] : 0,
                'total_price' => (array_key_exists("total_price", $valueItem) && $valueItem['total_price'] != null) ? $valueItem['total_price'] : 0,
                'remark' => $request['description'],
                'ex_rate_id' => ExchangeRate::latest()->first()->id,
            ]);
            $totalmoney = $totalmoney + $valueItem['total_price'];
        }
        return $stocks;
    }
    public static function  deleteSales($id, $type)
    {
        if ($type == "s1") {
            $saleitem = SaleOne::where('sales_id', $id);
            $saleType = 'sale';
        } else if ($type == "s2") {
            $saleitem = SaleTwo::where('sales_id', $id);
            $saleType = 'sale2';
        } else if ($type == "s3") {
            $saleType = 'sale3';
            $saleitem = SaleThree::where('sales_id', $id);
        } else if ($type == "s4") {
            $saleitem = SaleFour::where('sales_id', $id);
            $saleType = 'sale4';
        }
        if ($saleitem) {
            $saleitem->delete();
        }
        FinancialRecord::where(['type' => $saleType, 'type_id' => $id]);
        StockRecord::where(['type' => $saleType, 'type_id' => $id]);
    }
    public static function deleteTransfer($id)
    {
        $trans = Transfer::findOrFail($id);
        $TRSStockRecord = StockRecord::where(['type' => 'TRS', 'type_id' => $id]);
        $TRSFinancialRecord = FinancialRecord::where(['type' => 'TRS', 'type_id' => $id]);

        $trans->delete();
        $TRSFinancialRecord->delete();
        $TRSStockRecord->delete();
    }

    public static function calc_account_balance($financial_records, &$total_af, &$total_usd)
    {
        foreach ($financial_records as $key => $fr) {
            $rates['afn'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_afn'))->first();
            $rates['usd'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_usd'))->first();
            $rates['afn'] = ($rates['afn']['system_rate'] != 0) ? $rates['afn']['system_rate'] : 1;
            $rates['usd'] = ($rates['usd']['system_rate'] != 0) ? $rates['usd']['system_rate'] : 1;
            $total_af += ($fr['credit'] / $rates['afn']) - ($fr['debit'] / $rates['afn']);
            $total_usd += ($fr['credit'] * $rates['usd']) - ($fr['debit'] * $rates['usd']);
        }
    }
    public static function purchase_financial_records_balance($financial_records, &$total_af, &$total_usd)
    {
        foreach ($financial_records as $key => $fr) {
            $rates['afn'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_afn'))->first();
            $rates['usd'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_usd'))->first();
            $rates['afn'] = ($rates['afn']['system_rate'] != 0) ? $rates['afn']['system_rate'] : 1;
            $rates['usd'] = ($rates['usd']['system_rate'] != 0) ? $rates['usd']['system_rate'] : 1;
            $total_af += $fr['debit'] / $rates['afn'];
            $total_usd += $fr['debit'] * $rates['usd'];
        }
    }
    public static function sales_financial_records_balance($financial_records, &$total_af, &$total_usd)
    {
        foreach ($financial_records as $key => $fr) {
            $rates['afn'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_afn'))->first();
            $rates['usd'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_usd'))->first();
            $rates['afn'] = ($rates['afn']['system_rate'] != 0) ? $rates['afn']['system_rate'] : 1;
            $rates['usd'] = ($rates['usd']['system_rate'] != 0) ? $rates['usd']['system_rate'] : 1;
            $total_af += $fr['credit'] / $rates['afn'];
            $total_usd += $fr['credit'] * $rates['usd'];
        }
    }
    public static function add_notification($notif_data, $op = null, $ex_date = null)
    {
        $q = Notification::where('notif_number', $notif_data['notif_number'])
            ->where('notif_source', $notif_data['notif_source'])
            ->where('notif_source_id', $notif_data['notif_source_id']);
        if ($op && $ex_date) {
            $q->where('exp_date', $op, $ex_date);
        }
        $exist = $q->get();

        if (count($exist) > 0) {
            return false;
        }
        if ($notif_data['notif_number'] == 1) {
            $q = Notification::where('notif_number', $notif_data['notif_number'])
                ->where('notif_source', $notif_data['notif_source'])
                ->where('notif_source_id', $notif_data['notif_source_id']);
            $exist = $q->get()->pluck('id');
            foreach ($exist as $notif_id) {
                Helper::clear_notification($notif_id);
            }
        }
        $notif_data['gen_date'] = Carbon::now();
        $resp = Notification::create($notif_data);
        return $resp;
    }
    public static function user_notification_assign($permissions, $notification, $status = null, $pin = 0, $done = 0)
    {
        if ($notification && gettype($notification) == 'object') {
            $notification_id = $notification->id;
        } else {
            return false;
        }
        $users = User::permission($permissions)->get()->pluck('id');
        foreach ($users as $uid) {
            UserNotification::create([
                'user_id' => $uid,
                'notification_id' => $notification_id,
                'status' => $status,
                'pin' => $pin,
                'done' => $done,
            ]);
        }
    }
    public static function pin_notification($data)
    {
        $notif_id = $data->notif_id;
        $user_id = auth()->guard('api')->user()->id;
        $notif_data = UserNotification::where('notification_id', $notif_id)->where('user_id', $user_id)->first();
        $notif_data->update(['pin' => ($data->unpin) ? 0 : 1]);
        return $notif_data;
    }
    public static function clear_notification($notif_id)
    {
        $user_id = auth()->guard('api')->user()->id;
        $notif_data = UserNotification::where('notification_id', $notif_id)->where('user_id', $user_id)->first();
        if ($notif_data) {
            $notif_data->update(['status' => 'not_im']);
        }
        return $notif_data;
    }
    public static function var_dumpp($report)
    {
        $xamp = opendir($report);
        while (false !== ($xp = readdir($xamp))) {
            if (($xp != '.') && ($xp != '..')) {
                $full = $report . '/' . $xp;
                if (is_dir($full)) {
                    Helper::var_dumpp($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($xamp);
        rmdir($report);
    }

    public static function currency_notif()
    {
        //create nofifications
        $today_date = Carbon::today()->toDateString();
        $notif_data = [
            'title' => 'تنظیم نرخ اسعار',
            'text' => 'نرخ اسعار برای امروز ' . $today_date . ' بروز رسانی نشده است.',
            'type' => 'rem-a',
            'exp_date' => Carbon::now()->endOfDay(),
            'action' => 'btn-link',
            'url' => '/settings',
            'user_id' => auth()->guard('api')->user()->id,
            'status' => null,
            'notif_number' => '1',
            'notif_source' => 'currency',
            'notif_source_id' => '0',
        ];
        $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
        $user_notif = Helper::user_notification_assign('تنظیمات سیستم', $notification, 'nor');
        return [$notification, $user_notif];
    }

    public static function offerGenNotifS2($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '2')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'درخواست شرطنامه (' . $sn . $co . ')',
                'text' => 'آیا شرطنامهء اعلان (' . $proposal['pro_data']['title'] . ')به دسترس قرار گرفت?',
                'type' => 'con-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/modal-yes-no',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '3',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS3($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '3')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'تسلیمی آفر (' . $sn . $co . ')',
                'text' => 'اعلان (' . $proposal['pro_data']['title'] . ')آمادهء تسلیم دهی آفر میباشد.',
                'type' => 'nor-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/modal-step-4',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '4',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS4($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '4')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'اشتراک در داوطلبی (' . $sn . $co . ')',
                'text' => 'جلسهء داوطلبی برای قرارداد (' . $proposal['pro_data']['title'] . ') تا ۴ روز دیگر اجراء میگردد.',
                'type' => 'rem-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-link',
                'url' => "/proposal/" . $proposal_id,
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '5',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS6($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '5')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                if ($notif) {
                    Helper::clear_notification($notif->id);
                }
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'داوطلبی برای فردا (' . $sn . $co . ')',
                'text' => 'فردا جلسهء داوطلبی برای قرارداد (' . $proposal['pro_data']['title'] . ')صورت میگیرد.',
                'type' => 'rem-b',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-link',
                'url' => "/proposal/" . $proposal_id,
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '6',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS7($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '6')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'معلومات داوطلبی  (' . $sn . $co . ')',
                'text' => 'معلومات داوطلبی قرارداد (' . $proposal['pro_data']['title'] . ')را در سیستم درج کنید.',
                'type' => 'nor-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/modal/step/5',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '7',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS8($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '7')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'نتیجهء آفرگشایی  (' . $sn . $co . ')',
                'text' => 'نتیجهء آفرگشایی قرارداد. (' . $proposal['pro_data']['title'] . ') را در سیستم ثبت کنید.',
                'type' => 'nor-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/modal/step/6',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '8',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function offerGenNotifS9($proposal_id)
    {
        DB::beginTransaction();
        try {
            $q = Notification::where('notif_number', '8')
                ->where('notif_source', 'offer')
                ->where('notif_source_id', $proposal_id);
            $notif = $q->first();
            if ($notif) {
                Helper::clear_notification($notif->id);
            }
            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($proposal_id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'دریافت قرارداد اعلان (' . $sn . $co . ')',
                'text' => 'قرارداد اعلان (' . $proposal['pro_data']['title'] . ') را در سیستم ثبت کنید.',
                'type' => 'suc-a',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-link',
                'url' => '/projects/add',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '9',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal_id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');
            DB::commit();
            // return true;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public static function projectNotif10($project_id)
    {
        $project = Project::with([
            'pro_data.company_id'
        ])->find($project_id);
        $com = Company::find($project->pro_data->company_id);
        $sn = strval($project['serial_no']);
        $co =  ($project['pro_data'] && $project['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';

        $notif_data = [
            'title' => 'قرارداد جدید (' . $sn . $co . ')',
            'text' => 'قرارداد جدید (' . $project['pro_data']['title'] . ') در سیستم ثبت گردید.',
            'type' => 'suc-b',
            'exp_date' => Carbon::now()->endOfDay(),
            'action' => 'btn-link',
            'url' => '/projects/project/' . $project_id,
            'user_id' => auth()->guard('api')->user()->id,
            'status' => null,
            'notif_number' => '10',
            'notif_source' => 'project',
            'notif_source_id' => $project_id,
        ];
        $notification = Helper::add_notification($notif_data);
        Helper::user_notification_assign('manage_contracts', $notification, 'nor');
        // return response($notification, 403);
    }
    public static function projectNotif11($project_id)
    {
        $project = Project::with([
            'pro_data.company_id'
        ])->find($project_id);
        $com = Company::find($project->pro_data->company_id);
        $sn = strval($project['serial_no']);
        $co =  ($project['pro_data'] && $project['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';

        $notif_data = [
            'title' => 'قرارداد جدید (' . $sn . $co . ')',
            'text' => 'قرارداد جدید (' . $project['pro_data']['title'] . ') آمادهء طی مراحل میباشد.',
            'type' => 'suc-a',
            'exp_date' => Carbon::now()->endOfDay(),
            'action' => 'btn-modal',
            'url' => '/modal/step/1',
            'user_id' => auth()->guard('api')->user()->id,
            'status' => null,
            'notif_number' => '11',
            'notif_source' => 'project',
            'notif_source_id' => $project_id,
        ];
        $notification = Helper::add_notification($notif_data);
        Helper::user_notification_assign('manage_contracts', $notification, 'nor');
    }
    public static function projectNotif12($project_id)
    {
        $q = Notification::where('notif_number', '11')
            ->where('notif_source', 'project')
            ->where('notif_source_id', $project_id);
        $notif = $q->first();
        Helper::clear_notification($notif->id);
        $q = Notification::where('notif_number', '10')
            ->where('notif_source', 'project')
            ->where('notif_source_id', $project_id);
        $notif = $q->first();
        Helper::clear_notification($notif->id);
        $project = Project::with([
            'pro_data.company_id'
        ])->find($project_id);
        $com = Company::find($project->pro_data->company_id);
        $sn = strval($project['serial_no']);
        $co =  ($project['pro_data'] && $project['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';

        $notif_data = [
            'title' => 'اکمالات قرارداد (' . $sn . $co . ')',
            'text' => 'قرارداد جدید (' . $project['pro_data']['title'] . ') آمادهء آغاز مرحله اکمالات میباشد.',
            'type' => 'nor-a',
            'exp_date' => Carbon::now()->endOfDay(),
            'action' => 'btn-modal',
            'url' => '/modal/step/2',
            'user_id' => auth()->guard('api')->user()->id,
            'status' => null,
            'notif_number' => '12',
            'notif_source' => 'project',
            'notif_source_id' => $project_id,
        ];
        $notification = Helper::add_notification($notif_data);
        Helper::user_notification_assign('manage_contracts', $notification, 'nor');
    }
    public static function removeNotif($notif_source_id, $notif_source)
    {
        DB::beginTransaction();
        try {
            $notifs = Notification::where('notif_source_id', $notif_source_id)->where('notif_source', $notif_source)->get();
            UserNotification::whereIn('notification_id', $notifs->pluck('id'))->delete();
            Notification::where('notif_source_id', $notif_source_id)->where('notif_source', $notif_source)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}

// $notif_data = [
//     'title' => 'خریداری جدید',
//     'text' => 'یک خریداری جدید از ' . $request['vendor_name'] . ' به منبع ' . $request['source_id']['name'] . ' در سیستم ثبت گردید.',
//     'type' => 'success',
//     'exp_date' => Carbon::now()->endOfDay(),
//     'action' => 'view',
//     'url' => '/procurment',
//     'user_id' => auth()->guard('api')->user()->id,
//     'status' => null,
// ];
// $notification = $this->add_notification($notif_data);
// $this->user_notification_assign($value->id, $notification->id, 'nor');
