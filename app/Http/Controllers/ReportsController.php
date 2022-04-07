<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Helper\Helper;
use App\Models\Account;

use App\Models\Expense;
use App\Models\AccountType;
use App\Models\Client;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\FinancialRecord;

class ReportsController extends Controller
{

  public function allInOne(Request $request)
  {
    
    
    $data = array_fill(0, 14, 0);
    
    $data['14'] = Jalalian::forge(date('M'))->format('%B');
    $first = date("Y-m-d", strtotime("first day of this month"));
    $last = date("Y-m-d", strtotime("first day of next month"));

    $first_year = date("Y-m-d",strtotime("this year January 1st"));
    $last_year = date("Y-m-d",strtotime("this year December 31st"));
    // dd([$first_year, $last_year]);
    $data[0] = Client::whereBetween('created_at', [$first, $last])->count();
    
    
    $data[10] = FinancialRecord::where('credit', '>', 0)
      ->whereBetween('created_at', [$first, $last])->count();
    $data[11] = FinancialRecord::where('credit', '>', 0)
      ->whereBetween('created_at', [$first_year, $last_year])->count();

    
    $data[12] = Account::whereBetween('created_at', [$first, $last])->count();
    $data[13] = Account::whereBetween('created_at', [$first_year, $last_year])->count();

    $creditFinancialRecord = FinancialRecord::with('exchange_rate')
      ->where('credit', '>', 0)
      ->where('type', 'LIKE', 'sale%')
      ->whereBetween('created_at', [$first, $last])->get();
    $total_af = 0;
    $total_usd = 0;
    Helper::sales_financial_records_balance($creditFinancialRecord, $total_af, $total_usd);
    $data[1] = $total_af;

    $data[8] = Sale::whereBetween('created_at', [$first, $last])->count();
    $data[9] = Sale::whereBetween('created_at', [$first_year, $last_year])->count();
    
    $creditFinancialRecord = FinancialRecord::with('exchange_rate')
      ->where('credit', '>', 0)
      ->where('status', 'INC')
      ->whereBetween('created_at', [$first, $last])->get();
    $total_af = 0;
    $total_usd = 0;
    Helper::sales_financial_records_balance($creditFinancialRecord, $total_af, $total_usd);
    $data[2] = $total_af;

    $debitFinancialRecord = FinancialRecord::with('exchange_rate')
      ->where('debit', '>', 0)
      ->whereBetween('created_at', [$first, $last])
      ->where('status', 'EXP')
      ->get();
    $total_af = 0;
    $total_usd = 0;
    Helper::purchase_financial_records_balance($debitFinancialRecord, $total_af, $total_usd);
    $data[3] = $total_af;

    $data[4] = FinancialRecord::with('exchange_rate')
    ->where('credit', '>', 0)
    ->where('status', 'INC')
    ->whereBetween('created_at', [$first, $last])->count();
    $data[5] = FinancialRecord::with('exchange_rate')
    ->where('credit', '>', 0)
    ->where('status', 'INC')
    ->whereBetween('created_at', [$first_year, $last_year])->count();
    
    $data[6] = FinancialRecord::with('exchange_rate')
    ->where('debit', '>', 0)
    ->whereBetween('created_at', [$first, $last])
    ->where('status', 'EXP')->count();

    $data[7] = FinancialRecord::with('exchange_rate')
    ->where('debit', '>', 0)
    ->where('status', 'EXP')
    ->whereBetween('created_at', [$first_year, $last_year])->count();
    return $data;
  }
  public function saleReport(Request $request)
  {
    $sales1 = Sale::join('sales_ones AS s', 'sales.id', '=', 's.sales_id')
      ->whereBetween('sales.created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

    $sales2 = Sale::join('sales_twos AS s', 'sales.id', '=', 's.sales_id')
      ->whereBetween('sales.created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

    $sales3 = Sale::join('sales_threes AS s', 'sales.id', '=', 's.sales_id')
      ->whereBetween('sales.created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->selectRaw("s.sales_id, s.serial_no, s.total, s.service_cost, sales.type, sales.source_type, sales.source_id");

    $sales4 = Sale::join('sales_fours AS s', 'sales.id', '=', 's.sales_id')
      ->whereBetween('sales.created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->selectRaw("s.sales_id, s.serial_no, s.total, s.additional_cost as service_cost, sales.type, sales.source_type, sales.source_id");
    if ($request->saletype == 'sale') {
      $unionQuery = $sales1;
    } else if ($request->saletype == 'sale2') {
      $unionQuery = $sales2;
    } else if ($request->saletype == 'sale3') {
      $unionQuery = $sales3;
    } else if ($request->saletype == 'sale4') {
      $unionQuery = $sales3;
    } else {
      $unionQuery = $sales1->union($sales2)->union($sales3)->union($sales4);
    }
    $sales_data = $unionQuery->orderBy('sales_id', 'desc')->get();
    foreach ($sales_data as $key => $sale) {
      $base = Sale::findOrFail($sale['sales_id']);
      if ($base->type == "s1") {
        $sale = Sale::with(['saleS1.project.pro_data.client', 'source_id'])->where('id', $sale['sales_id'])->first();
        $sales_data[$key]['sales'] = $sale->saleS1;
        $sales_data[$key]['sales']['pro_items'] = StockRecord::with([
          'operation_id',
          'item.measurment_unites_sub',
          'item.uom_id',
          'item.type',
        ])->where('type', 'sale')->where('type_id', $sale->id)->get();
      } else if ($base->type == "s3") {
        $sale = Sale::with(['saleS3.project.pro_data', 'source_id'])->where('id', $sale['sales_id'])->first();
        $sales_data[$key]['sales'] = $sale->saleS3;
        $sales_data[$key]['sales']['pro_items'] = StockRecord::with([
          'operation_id',
          'item.measurment_unites_sub',
          'item.uom_id',
          'item.type',
        ])->where('type', 'sale3')->where('type_id', $sale->id)->get();
      } else if ($base->type == "s2") {
        $sales_data[$key]['sales']['pro_items'] = StockRecord::with([
          'operation_id',
          'item.measurment_unites_sub',
          'item.uom_id',
          'item.type',
        ])->where('type', 'sale2')->where('type_id', $sale->id)->get();
      } else if ($base->type == "s4") {
        $sales_data[$key]['sales']['pro_items'] = StockRecord::with([
          'operation_id',
          'item.measurment_unites_sub',
          'item.uom_id',
          'item.type',
        ])->where('type', 'sale4')->where('type_id', $sale->id)->get();
      }
    }
    $data = $request;
    $data['report'] = $sales_data;
    // return $data;/
    return view('reports.sales_report', compact('data', $data));
  }

  public function expenseReport(Request $request)
  {
    $expenses_data = Expense::with('user', 'currency')
      ->whereBetween('created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->get();
    foreach ($expenses_data as $key => $value) {
      $creditFinancialRecord = FinancialRecord::with('account')->where('type_id', $value->id)->where('credit', '>', 0)->where('type', 'EXP')->first();
      $expenses_data[$key]['credit_account'] = $creditFinancialRecord->account;
      $expenses_data[$key]['credit'] = $creditFinancialRecord->credit;
      $debitFinancialRecord = FinancialRecord::with('account')->where('type_id', $value->id)->where('debit', '>', 0)->where('type', 'EXP')->first();
      $expenses_data[$key]['debit_account'] = $debitFinancialRecord->account;
      $expenses_data[$key]['debit'] = $debitFinancialRecord->debit;
    };
    // return $expenses_data;
    $data = $request;
    $data['report'] = $expenses_data;
    return view('reports.expenses_report', compact('data', $data));
  }

  public function benefitReport(Request $request)
  {
    $income_data_total = FinancialRecord::whereBetween('created_at', [$request->frdate, $request->todate])
      ->where('type', '<>', 'purchase')->where('type', '<>', 'EXP')->where('status', 'INC')->sum('credit');

    $start = strtotime($request->frdate);
    $end = strtotime($request->todate);
    $range = [];
    $date = strtotime("-1 day", $start);

    $key = 0;
    while ($date < $end) {
      $s = date('Y-m-d', $date);
      $date = strtotime("+1 day", $date);
      $income_data[$key]['date'] = Jalalian::forge($date)->format('%d %B ،%Y');
      $e = date('Y-m-d', $date);
      $income_data[$key]['ammount'] = FinancialRecord::whereBetween('created_at', [$s, $e])
        ->where('type', '<>', 'purchase')->where('type', '<>', 'EXP')->where('status', 'INC')->sum('credit');
      $key += 1;
    }
    // return $income_data;

    $data = $request;
    $data['report'] = $income_data;
    $data['income_data_total'] = $income_data_total;
    return view('reports.benefits_report', compact('data', $data));
  }

  public function accountsReport(Request $request)
  {
    $accounts_types = AccountType::with('accounts.financial_records.exchange_rate')
      ->whereBetween('created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      // ->has('accounts')
      // ->has('accounts.financial_records')
      // ->has('accounts.financial_records.exchange_rate')
      ->get();
    foreach ($accounts_types as $key1 => &$type) {
      $this->loadType($type);
      foreach ($type['accounts'] as $key2 => &$acc) {
        $credit_total_af = 0;
        $debit_total_af = 0;
        $credit_total_usd = 0;
        $debit_total_usd = 0;

        foreach ($acc['financial_records'] as $key => $fr) {
          $rates['afn'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_afn'))->first();
          $rates['usd'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_usd'))->first();
          $rates['afn'] = ($rates['afn']['system_rate'] != 0) ? $rates['afn']['system_rate'] : 1;
          $rates['usd'] = ($rates['usd']['system_rate'] != 0) ? $rates['usd']['system_rate'] : 1;
          $credit_total_af += $fr['credit'] / $rates['afn'];
          $debit_total_af += $fr['debit'] / $rates['afn'];
          $credit_total_usd += $fr['credit'] * $rates['usd'];
          $debit_total_usd += $fr['debit'] * $rates['usd'];
        }

        $accounts_types[$key1]['accounts'][$key2]['fr'] = [
          'afn_debit' => round($debit_total_af),
          'usd_debit' => round($debit_total_usd),
          'afn_credit' => round($credit_total_af),
          'usd_credit' => round($credit_total_usd),
        ];
      }
    }
    $data = $request;
    $data['report'] = $accounts_types;
    return view('reports.accounts_report', compact('data', $data));
  }
  public function loadType($account)
  {
    if ($account['type_id'] != null) {
      $account['type_id'] = AccountType::where('id', $account['type_id'])->first();
      $this->loadType($account['type_id']);
    }
  }

  public function account_detailsReport(Request $request)
  {
    $frs = FinancialRecord::with('exchange_rate')
      ->where('account_id', $request->account)
      ->whereBetween('created_at', [date("Y-m-d", strtotime($request->frdate)), date("Y-m-d", strtotime($request->todate))])
      ->get();
    foreach ($frs as $key => &$fr) {
      $rates['afn'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_afn'))->first();
      $rates['usd'] = ExchangeRate::where('counter', $fr['exchange_rate']['counter'])->where('currency_id', config('app.currency_usd'))->first();
      $rates['afn'] = ($rates['afn']['system_rate'] != 0) ? $rates['afn']['system_rate'] : 1;
      $rates['usd'] = ($rates['usd']['system_rate'] != 0) ? $rates['usd']['system_rate'] : 1;
      $fr['afn_credit'] = $fr['credit'] / $rates['afn'];
      $fr['afn_debit'] = $fr['debit'] / $rates['afn'];
      $fr['usd_credit'] = $fr['credit'] * $rates['usd'];
      $fr['usd_debit'] = $fr['debit'] * $rates['usd'];
      $fr['created_fa'] = Jalalian::forge($fr->created_at)->format('%d %B ،%Y');
    }
    $data = $request;
    $data['account'] = Account::find($request->account);
    $data['report'] = $frs;
    // return $data;
    return view('reports.accouts_details_report', compact('data', $data));
  }

  public function tarazReport(Request $request)
  {
    $data = $request;
    return view('reports.sales', compact('data', $data));
  }
}
