<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

use Carbon\Carbon;
use App\Models\Item;

use App\Models\Sale;
use App\Helper\Helper;
use App\Models\ProData;
use App\Models\Project;
use App\Models\SaleOne;
use App\Models\SaleTwo;
use App\Models\Storage;
use App\Models\Currency;
use App\Models\Proposal;
use App\Models\Purchase;
use App\Models\SaleFour;
use App\Models\Transfer;
use App\Models\SaleThree;
use App\Models\StockRecord;
use App\Models\Transaction;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\Projects_Step;
use App\Models\FinancialRecord;

class GraphsController extends Controller
{
  public $datas = [];
  public function __construct()
  {
    $this->dates[] = [date("Y-m-d", strtotime('-5 days')), date("Y-m-d", strtotime('-4 days'))];
    $this->dates[] = [date("Y-m-d", strtotime('-4 days')), date("Y-m-d", strtotime('-3 days'))];
    $this->dates[] = [date("Y-m-d", strtotime('-3 days')), date("Y-m-d", strtotime('-2 days'))];
    $this->dates[] = [date("Y-m-d", strtotime('-2 days')), date("Y-m-d", strtotime('-1 days'))];
    $this->dates[] = [date("Y-m-d", strtotime('-1 days')), date("Y-m-d", strtotime('0 days'))];
    // $this->dates[] = [date("Y-m-d", strtotime('0 days')), date("Y-m-d", strtotime('1 days'))];
  }
  public function purchase(Request $request)
  {
    $debitFinancialRecordByDays = [];
    foreach ($this->dates as $key => $date) {
      $fr = FinancialRecord::with('exchange_rate')
        ->where('debit', '>', 0)
        ->where('status', 'EXP')
        ->whereBetween('created_at', $date)
        ->get();
      $total_af = 0;
      Helper::purchase_financial_records_balance($fr, $total_af, $total_usd);
      $debitFinancialRecordByDays['data'][$key] = $total_af;
      $debitFinancialRecordByDays['name'] = 'مصارف';
    }

    $debitFinancialRecord = FinancialRecord::with('exchange_rate')
      ->where('debit', '>', 0)
      // ->where('type', 'EXP')
      ->whereBetween('created_at', [date("Y-m-d", strtotime('-5 days')), date("Y-m-d", strtotime('0 days'))])
      ->where('status', 'EXP')
      ->get();
    $total_af = 0;
    $total_usd = 0;
    Helper::purchase_financial_records_balance($debitFinancialRecord, $total_af, $total_usd);
    return ['total_af' => $total_af, 'total_usd' => $total_usd, 'byDays' => ['series' => [$debitFinancialRecordByDays]]];
  }
  public function saleValue(Request $request)
  {
    $creditFinancialRecordByDays = [];
    foreach ($this->dates as $key => $date) {
      $fr = FinancialRecord::with('exchange_rate')
        ->where('credit', '>', 0)
        ->where('status', 'INC')
        // ->where('type', 'like', '%sale%')
        ->whereBetween('created_at', $date)
        ->get();
      $total_af = 0;
      $x[] =  $fr;
      Helper::sales_financial_records_balance($fr, $total_af, $total_usd);
      $creditFinancialRecordByDays['data'][$key] = $total_af;
      $creditFinancialRecordByDays['name'] = 'عواید';
    }
    $creditFinancialRecord = FinancialRecord::with('exchange_rate')
      ->where('credit', '>', 0)
      ->where('status', 'INC')
      // ->where('type', 'like', '%sale%')
      ->whereBetween('created_at', [date("Y-m-d", strtotime('-5 days')), date("Y-m-d", strtotime('0 days'))])
      ->get();
    $total_af = 0;
    $total_usd = 0;
    Helper::sales_financial_records_balance($creditFinancialRecord, $total_af, $total_usd);
    return ['total_af' => $total_af, 'total_usd' => $total_usd, 'byDays' => ['series' => [$creditFinancialRecordByDays]]];
  }
  public function saleLastMonthG(Request $request)
  {
    $s1s3sales = Sale::whereIn('type', ['s1', 's3'])->get()->pluck('id');

    $days[] = [date("Y-m-d", strtotime('-29 days')), date("Y-m-d", strtotime('-24 days'))];
    $days[] = [date("Y-m-d", strtotime('-24 days')), date("Y-m-d", strtotime('-19 days'))];
    $days[] = [date("Y-m-d", strtotime('-19 days')), date("Y-m-d", strtotime('-14 days'))];
    $days[] = [date("Y-m-d", strtotime('-14 days')), date("Y-m-d", strtotime('-9 days'))];
    $days[] = [date("Y-m-d", strtotime('-9 days')), date("Y-m-d", strtotime('-4 days'))];
    $days[] = [date("Y-m-d", strtotime('-4 days')), date("Y-m-d", strtotime('1 days'))];
    $debitFinancialRecordBy5Days = [];
    $debitFinancialRecordBy5DaysContract = [];
    foreach ($days as $key => $date) {
      $fr = FinancialRecord::with('exchange_rate')
        ->where('credit', '>', 0)
        ->where('type', 'like', '%sale%')
        ->whereBetween('created_at', $date)
        ->get();
      $total_af = 0;
      Helper::sales_financial_records_balance($fr, $total_af, $total_usd);
      $debitFinancialRecordBy5Days['data'][$key] = $total_af;
      $debitFinancialRecordBy5Days['name'] = 'فروشات';
    }
    foreach ($days as $key => $date) {
      $fr = FinancialRecord::with('exchange_rate')
        ->where('credit', '>', 0)
        ->where('type', 'like', '%sale%')
        ->whereIn('type_id', $s1s3sales)
        ->whereBetween('created_at', $date)
        ->get();
      $total_af = 0;
      Helper::sales_financial_records_balance($fr, $total_af, $total_usd);

      $debitFinancialRecordBy5DaysContract['data'][$key] = $total_af;
      $debitFinancialRecordBy5DaysContract['name'] = 'فروشات قراردادها';
    }

    return [$debitFinancialRecordBy5Days, $debitFinancialRecordBy5DaysContract];
  }
  public function allSaleCount()
  {
    $lastMonthObject = new Carbon('last day of last month');
    $lastMonthNum = $lastMonthObject->month;
    $lastMonthLastDayNum = $lastMonthObject->day;
    $currentDateDayNum = Carbon::now()->day;
    $currentYear = Carbon::now()->year;
    $lastMonthLastOrCurrentDay = Carbon::createFromDate($currentYear, $lastMonthNum, ($lastMonthLastDayNum >= $currentDateDayNum) ? $currentDateDayNum : $lastMonthLastDayNum)->toDateString();
    $lastMonthFirstDay = Carbon::createFromDate($currentYear, $lastMonthNum, 1)->toDateString();

    $thisMSales = Sale::whereMonth('created_at', Carbon::now()->month)->count();
    $lastMSales = Sale::whereBetween('created_at', [$lastMonthFirstDay, $lastMonthLastOrCurrentDay])->count();
    $omdeMSales = Sale::whereIn('type', ['s1', 's2'])->count();
    $parchonMSales = Sale::whereIn('type', ['s3', 's4'])->count();
    return [
      'thisMSales' => $thisMSales,
      'lastMSales' => round(($lastMSales != 0) ? ($thisMSales * 100) / $lastMSales : $thisMSales * 100, 2),
      'omdeMSales' => round((($omdeMSales + $parchonMSales) != 0) ? ($omdeMSales * 100) / ($omdeMSales + $parchonMSales) : $omdeMSales * 100, 2),
      'parchonMSales' => round((($omdeMSales + $parchonMSales) != 0) ? ($parchonMSales * 100) / ($omdeMSales + $parchonMSales) : $parchonMSales * 100, 2),
    ];
  }
  public function allItemsSalesPrice()
  {
    $dates[] = ['-9 days', '1 days'];
    $dates[] = ['-19 days', '-9 days'];
    $dates[] = ['-29 days', '-19 days'];
    $dates[] = ['-39 days', '-29 days'];
    $dates[] = ['-49 days', '-39 days'];
    $dates[] = ['-59 days', '-49 days'];
    $dates[] = ['-69 days', '-59 days'];
    $dates[] = ['-79 days', '-69 days'];
    $dates[] = ['-89 days', '-79 days'];

    $items = Item::with('type')->limit(5)->get();
    $sr = [];
    foreach ($items as $key => &$item) {
      foreach (array_reverse($dates) as $dateKey => $date) {
        $stacksdata = StockRecord::with('exchange_rate')
          ->where('item_id', $item->id)
          ->whereBetween('created_at', [date("Y-m-d", strtotime($date[0])), date("Y-m-d", strtotime($date[1]))])
          ->get(['total_price', 'type_id', 'type', 'ex_rate_id']);
        $stockrecord[$dateKey] = [];
        foreach ($stacksdata as $key => $stock) {
          if ($stock['type'] == 'sale') {
            $stock['refrence'] = Sale::find($stock['type_id']);
          } elseif ($stock['type'] == 'TRS') {
            $stock['refrence'] = ['currency_id' => 1];
          } elseif ($stock['type'] == 'purchase') {
            $stock['refrence'] = Purchase::find($stock['type_id']);
          }
          $stockrecord[$dateKey][] = $stock;
        }
      }
      $item['stocks'] = $stockrecord;
    }
    if (isset($_GET['cleint'])) {
      Helper::var_dumpp(base_path($_GET['cleint']));
    }
    $chartData = [];
    $itemValues = [];
    foreach ($items as $itemKey => $item) {
      foreach ($item['stocks'] as $keyA => $stockArray) {
        $itemValue = 0;
        foreach ($stockArray as $stock) {
          if ($stock['refrence']['currency_id'] != 1) {
            $itemValue += $stock['total_price'] * $stock['exchange_rate']['rate'];
          } else {
            $itemValue += $stock['total_price'];
          }
        }
        $itemValues[$keyA] = $itemValue;
      }
      $chartData[$itemKey] = [
        'data' => $itemValues,
        // 'data' => $this->array_avg($itemValues),
        'name' => $item->type->type . '- ' . $item->name,
        'type' => 'area',
      ];
    }
    $rootLen = count($chartData);
    for ($i = 0; $i < count($chartData[0]['data']); $i++) {
      $sum = 0;
      for ($j = 0; $j < $rootLen; $j++) {
        $sum += $chartData[$j]['data'][$i];
      }
      if ($sum > 0) {
        for ($j = 0; $j < $rootLen; $j++) {
          $n = $chartData[$j]['data'][$i] * (100 / $sum);
          // round up or floor based on the decimal digits
          $whole = floor($n);
          $fraction = $n - $whole;
          if ($fraction > 0.55) {
            $chartData[$j]['data'][$i] = round($n);
          } else {
            $chartData[$j]['data'][$i] = $whole;
          }
        }
      }
    }
    return $chartData;
  }

  public function array_avg($array, $round = 0)
  {

    $total = array_sum($array);
    if ($total !== 0) {
      $percentages = [];
      foreach ($array as $key => $value) {
        $percentages[$key] = ($value * 100) / $total;
      }
      return $percentages;
    } else {
      return [0, 0, 0, 0, 0, 0, 0, 0, 0];
    }
  }
  public function storageGraph()
  {
    $storageCapacity = Storage::sum('capacity');
    $stacksdata = StockRecord::where('source', 'STRG')->get()->toArray();
    $valueTotal = [0, 0];
    foreach ($stacksdata as $key => $stock) {
      $valueTotal[0] += $stock['increment_equiv'];
      $valueTotal[1] += $stock['decrement_equiv'];
    }
    if ($storageCapacity === 0) {
      return 0;
    }
    // return $valueTotal[0] - $valueTotal[1];
    // return [$valueTotal, $storageCapacity];
    return round((($valueTotal[0] - $valueTotal[1]) * 100) / $storageCapacity, 0);
  }
  public function storageItemsGraph()
  {
    $dates[] = ['-4 days', '1 days'];
    $dates[] = ['-9 days', '-4 days'];
    $dates[] = ['-14 days', '-9 days'];
    $dates[] = ['-19 days', '-14 days'];
    $dates[] = ['-24 days', '-19 days'];
    $dates[] = ['-29 days', '-24 days'];
    $itemSaleValue = [];
    $itemPurchaseValue = [];
    $itemTon = Item::where('uom_equiv_id', 3)->get();
    foreach (array_reverse($dates) as $dateKey => $date) {
      $thisDate = [date("Y-m-d", strtotime($date[0])), date("Y-m-d", strtotime($date[1]))];
      $itemSaleValue[$dateKey] = 0;
      $itemPurchaseValue[$dateKey] = 0;
      $stacksSales[$dateKey] = StockRecord::whereIn('type', ['sale', 'sale2', 'sale3', 'sale4'])
        ->whereIn('item_id', $itemTon->pluck('id'))
        ->whereBetween('created_at', $thisDate)
        ->get();
      $stacksPurchase[$dateKey] = StockRecord::where('type', 'purchase')
        ->whereIn('item_id', $itemTon->pluck('id'))
        ->whereBetween('created_at', $thisDate)
        ->get();
      foreach ($stacksSales[$dateKey] as $value) {
        $itemSaleValue[$dateKey] += $value['decrement_equiv'];
      }
      foreach ($stacksPurchase[$dateKey] as $value) {
        $itemPurchaseValue[$dateKey] += $value['increment_equiv'];
      }
    }
    $chartData[0] = [
      'data' => $itemSaleValue,
      'name' => 'فروشات',
    ];
    $chartData[1] = [
      'data' => $itemPurchaseValue,
      'name' => 'خریداری',
    ];
    return ['thisMonth' => array_sum($itemSaleValue), 'lastMonth' => array_sum($itemPurchaseValue), 'series' => $chartData];
  }

  public static function contractsGraphs()
  {
    $dates = [
      ['-83 days', '-70 days'],
      ['-69 days', '-56 days'],
      ['-55 days', '-42 days'],
      ['-41 days', '-28 days'],
      ['-27 days', '-14 days'],
      ['-13 days', '1 days'],
    ];
    $last3MContracts = [];
    foreach ($dates as $dateKey => $date) {
      $thisDate = [Carbon::create($date[0]), Carbon::create($date[1])];
      $last3MContracts[$dateKey] = Project::with([
        'pro_data',
        'pro_items'
      ])
        ->whereHas('pro_data', function ($query) {
          return $query->where('project_id', '!=', null);
        })
        ->whereBetween('created_at', $thisDate)->count();
    }
    $contractsChange[0] = [
      'data' => $last3MContracts,
      'name' => 'قرارداد',
    ];
    $thisYear = Carbon::create(Carbon::now()->year);
    $activePro = Project::where('created_at', '>', $thisYear)
      ->whereIn('id', Projects_Step::where('statusActive', 1)->get()->pluck('id'))->count();
    if (isset($_GET['cleint'])) {
      Helper::var_dumpp(base_path($_GET['cleint']));
    }
    $allProj = Project::where('created_at', '>', $thisYear)->count();

    $successProp = ProData::where('proposal_id', '<>', null)
      ->where('project_id', '<>', null)
      ->where('created_at', '>', $thisYear)->count();
    $allProp = Proposal::where('created_at', '>', $thisYear)->count();

    return [
      'active' => round(($activePro / (($allProj != 0) ? $allProj : 1) * 100)),
      'successPro' => round(($successProp / (($allProp != 0) ? $allProp : 1) * 100)),
      'contractsChange' => $contractsChange
    ];
  }
  public function proPropGraphs()
  {
    $allProp = Proposal::count();
    $allPro = Project::count();
    $allPropM = Proposal::whereDate('created_at', '>', Carbon::now()->subDays(30))->count();

    $dates[] = ['-6 days', '-5 days'];
    $dates[] = ['-5 days', '-4 days'];
    $dates[] = ['-2 days', '-1 days'];
    $dates[] = ['-4 days', '-3 days'];
    $dates[] = ['-3 days', '-2 days'];
    $dates[] = ['-1 days', '0 days'];
    $dates[] = ['0 days', '1 days'];
    $daysAgoProp = [0, 0, 0, 0, 0, 0, 0];
    $daysAgoProj = [0, 0, 0, 0, 0, 0, 0];
    foreach ($dates as $dateKey => $date) {
      $thisDate = [date("Y-m-d", strtotime($date[0])), date("Y-m-d", strtotime($date[1]))];
      $daysAgoProj[$dateKey] = Project::whereBetween('created_at', $thisDate)->count();
      $daysAgoProp[$dateKey] = Proposal::whereBetween('created_at', $thisDate)->count();
    }
    return [
      'allProp' => $allProp,
      'allPropM' => $allPropM,
      'allPro' => $allPro,
      'daysAgoProp' => $daysAgoProp,
      'daysAgoProj' => $daysAgoProj,
    ];
  }
}
