<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Account;
use App\Models\Project;
use App\Models\Storage;

use App\Models\Currency;
use App\Models\Proposal;
use App\Models\Inventory;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\Fuel_despenser;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use App\Models\Fuel_station_storage;
use Illuminate\Support\Facades\Response;

class PrinterController extends Controller
{

    public function printProject(Request $request){
        $data = Project::with([
            'pro_data.client',
            'pro_data.company',
            'pro_items.operation_id',
            'pro_items.item.type',
            'pro_items.item.measurment_unites_sub',
            'pro_items.item.measurment_unites_min',
            'pro_items.item.type',
            'pro_items.unit_id',
            'pro_items.uom_equiv_id',
        ])->find($request->pid);
        $data->printname = $request->name;
        return view('printers.project', compact('data', $data));
    }
    public function printProposal(Request $request){
        // return $request;
        $data = Proposal::with([
            'pro_data.client',
            'pro_data.company',
            'pro_items.operation_id',
            'pro_items.item.type',
            'pro_items.item.measurment_unites_sub',
            'pro_items.item.measurment_unites_min',
            'pro_items.item.type',
            'pro_items.unit_id',
            'pro_items.uom_equiv_id',
        ])->find($request->pid);
        $data->printname = $request->name;
        // return $data;
        return view('printers.proposal', compact('data', $data));
    }

    public function print(Request $request)
    {
        if($request->entity == 'sale'){
            $data = $this->loadSale($request->id, $request);
        }
        if($request->code == true){
            return $data;
        }
        if($data->type == 's1'){
            return view('printers.sales', compact('data', $data));
        }else if($data->type == 's2'){
            return view('printers.sales2', compact('data', $data));
        }else if($data->type == 's3'){
            return view('printers.sales3', compact('data', $data));
        }else if($data->type == 's4'){
            return view('printers.sales4', compact('data', $data));
        }

    }



    public function loadSale($id, $request){
        $base = Sale::findOrFail($id);
        if ($base->type == "s1") {
            $sale = Sale::with(['saleS1.project.pro_data.client', 'source'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS1;
            $sale['client'] = $sale->saleS1->project->pro_data->client->name;
            $st_type = 'sale';
        } else if ($base->type == "s2") {
            $sale = Sale::with(['saleS2.client', 'source'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS2;
            $st_type = 'sale2';
        } else if ($base->type == "s3") {
            $sale = Sale::with(['saleS3.project.pro_data.client', 'source'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS3;
            $st_type = 'sale3';
        } else if ($base->type == "s4") {
            $sale = Sale::with(['saleS4.client', 'source'])->where('id', $id)->first();
            $sale['sales'] = $sale->saleS4;
            $st_type = 'sale4';
        }
        $sale->currency_id = Currency::find($sale->currency_id);
        $sale->bank_account = Account::find($sale->bank_account);
        $sale['stock_records'] = StockRecord::where('type', $st_type)->where('type_id', $sale->id)
            ->with([
                'item',
                'item.measurment_unites_sub',
                'item.measurment_unites_min',
                'uom_equiv_id',
                'uom_id',
                'operation_id',
                'measur_unit',
                'source_id'
            ])->get();
        $sale['printname'] = $request->name;
        return $sale;
    }
}
