<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\Sale;

use App\Helper\Helper;
use App\Models\Account;
use App\Models\ProData;
use App\Models\ProItem;
use App\Models\Project;
use App\Models\Currency;
use App\Models\StockRecord;
use App\Models\ExchangeRate;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;
use App\Models\Projects_Step;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::with([
            'pro_data.client',
            'pro_data.company_id',
            'pro_items.operation_id',
            'pro_items.item_id.type',
            'pro_items.item_id.uom_id',
            'pro_items.item_id.uom_equiv_id',
            'pro_items.item_id.type',
            'pro_items.unit_id',
            'pro_items.uom_equiv_id',
        ])
            ->whereHas('pro_data', function ($query) {
                return $query->where('project_id', '!=', null);
            })
            ->latest()->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $default_afn = Currency::where('sign_en', 'AFN')->first()->id;
        DB::beginTransaction();
        try {
            // return $request;
            // return Carbon::parse($request->issue_date);
            $this->validate($request, [
                'item' => 'required',
                // 'issue_date' => 'required|date',
                // 'type' => 'required',
                // 'price' => 'required:max:20',
                // 'offer_date' => 'required|date',
                // 'close_date' => 'required|date',
                // 'offer_price' => 'required|max:20',
                // 'project_price' => 'required|max:20',
                // 'announce_id' => 'required',
                // 'organization_id' => 'required',
                // 'title' => 'required|min:3',
                // 'issue_address' => 'required|min:3',
                // 'source_address' => 'required|min:3',
            ]);
            if (gettype($request->client_id) != 'integer') {
                $request['client_id'] = $request->client_id['id'];
            }
            if (gettype($request->proposal_id) != 'integer') {
                $request['proposal_id'] = ($request->proposal_id) ? $request->proposal_id['id'] : null;
            }
            // Get The last serial number for the proposal.
            $company_sign = $request->company_id['sign'];
            $serial_no = Helper::getSerialNo('pro-' . $company_sign, 'pro');
            $request['serial_no'] = $serial_no->value;

            $project = Project::create($request->all());

            if ($request['proposal_id']) {

                $proData = [
                    'client_id' => $request->client_id,
                    'title' => $request->title,
                    'company_id' => $request->company_id['id'],
                    'reference_no' => $request->reference_no,
                    'pr_worth' => $request->pr_worth,
                    'deposit' => $request->deposit,
                    'tax' => $request->tax,
                    'transit' => $request->transit,
                    'others' => $request->others,
                    'currency_id' => $default_afn,
                    'total_price' => $request->total_price,
                    // 'proposal_id' => null,
                    'project_id' => $project->id,
                ];
                ProData::where('proposal_id', $request['proposal_id'])->update($proData);
            } else {
                $proData = [
                    'project_id' => $project->id,
                    'client_id' => $request->client_id,
                    'company_id' => $request->company_id['id'],
                    'title' => $request->title,
                    'reference_no' => $request->reference_no,
                    'pr_worth' => $request->pr_worth,
                    'deposit' => $request->deposit,
                    'tax' => $request->tax,
                    'transit' => $request->transit,
                    'others' => $request->others,
                    'currency_id' => $default_afn,
                    'total_price' => $request->total_price,
                ];
                ProData::create($proData);
            }

            // Create Pro Items Record for selected Items
            foreach ($request->item as $key => $item) {
                if (gettype($item['item_id']) != 'integer') {

                    $item = [
                        'unit_id' => $item['item_id']['uom_id']['id'],
                        'uom_equiv_id' => $item['item_id']['uom_equiv_id']['id'],
                        'item_id' => $item['item_id']['id'],
                        'project_id' => $project->id,
                        'operation_id' => $item['operation_id']['id'],
                        'ammount' => $item['ammount'],
                        'unit_price' => $item['unit_price'],
                        'equivalent' => $item['equivalent'],
                        'density' => $item['density'],
                        'total_price' => $item['total_price'],
                    ];
                    ProItem::create($item);
                }
                // return $item;
            }

            // Create the Account beside the project
            $data = [
                'user_id' => auth()->guard('api')->user()->id,
                'type_id' => config('app.contract_account_type'),
                'ref_code' => $project->id,
                'name' => $request->title,
                'status' => 1,
                'description' => $request->title,
                // 'system' => $request->system,    
            ];
            if ($account = Account::create($data)) {
                // Create opening FR for the created Projet
                $data = [
                    'type' => 'project', // here the type of financial record is project
                    'type_id' => $project->id, //Project Id will be used here as type id
                    'account_id' => $account->id,
                    'description' => 'Dynamically Created For Project.' . $project->id,
                    'currency_id' => config('app.currency_afn'),
                    'credit' => $request->pr_worth,
                    'debit' => 0,
                    'ex_rate_id' => ExchangeRate::latest()->first()->id,
                    'status' => 'opn'
                ];
                FinancialRecord::create($data);
            }
            DB::commit();
            Helper::projectNotif10($project['id']);
            Helper::projectNotif11($project['id']);
            return $project;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return Project::with([
            'pro_data.client',
            'pro_data.company_id',
            'pro_items.item_id',
            'pro_items.operation_id',
            'pro_items.item_id.uom_equiv_id',
            'pro_items.item_id.uom_id',
            'pro_items.item_id.type',
            'proposal_id.pro_data.client'
        ])->latest()->find($project->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ProData::join('clients AS c', 'pro_data.client_id', '=', 'c.id')
            ->selectRaw("c.name, pro_data.title")->where('pro_data.project_id', $id)->get();
    }
    public function changeStep(Request $request, $id)
    {
        $project_step = Projects_Step::where(['project_id' => $id])->first();
        $request->adminis_prove = $request->adminis_prove;
        $request->finishcontract = $request->finishcontract;
        if($project_step){
            if ($request->step == 2) {
                Helper::projectNotif12($id);
            }
            $project_step->update($request->all());
            return [$request->all(), Projects_Step::where(['project_id' => $id])->first()];
        } else {
            Projects_Step::create([
                'step' => 1,
                'project_id' => $id,
                'is_ekmalat_allowed' => $request['is_ekmalat_allowed'],
                'mactob_sending' => $request['mactob_sending'],
                'adminis_prove' => $request['adminis_prove'],
                'finishcontract' => $request['finishcontract'],
                'statusActive' => $request['statusActive'],
                'setting_and_baqyat' => $request['setting_and_baqyat'],
            ]);
        }
    }
    public function getprojectStep($id)
    {
        $projectstep = Projects_Step::where(['project_id' => $id])->get();
        if (sizeof($projectstep) >= 1) {
            return $projectstep;
        } else {
            return response(['status' => 'no']);
        }
        // 
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $default_afn = Currency::where('sign_en', 'AFN')->first()->id;
        DB::beginTransaction();
        try {
            if (gettype($request->client_id) != 'integer') {
                $request['client_id'] = $request->client_id['id'];
            }
            if (gettype($request->proposal_id) != 'integer') {
                $request['proposal_id'] = ($request->proposal_id) ? $request->proposal_id['id'] : null;
            }
            // Get The last serial number for the proposal.
            $company_sign = $request->company_id['sign'];
            $serial_no = Helper::getSerialNo('pro-' . $company_sign, 'pro');
            $request['serial_no'] = $serial_no->value;

            $project = Project::find($id);
            $project->update($request->all());

            if ($request['proposal_id']) {

                $proData = [
                    'client_id' => $request->client_id,
                    'title' => $request->title,
                    'company_id' => $request->company_id['id'],
                    'reference_no' => $request->reference_no,
                    'pr_worth' => $request->pr_worth,
                    'deposit' => $request->deposit,
                    'tax' => $request->tax,
                    'transit' => $request->transit,
                    'others' => $request->others,
                    'total_price' => $request->total_price,
                    'project_id' => $project->id,
                ];
                ProData::where('proposal_id', $request['proposal_id'])->update($proData);
            } else {
                ProData::where('project_id', $id)->delete();
                $proData = [
                    'project_id' => $project->id,
                    'client_id' => $request->client_id,
                    'company_id' => $request->company_id['id'],
                    'title' => $request->title,
                    'reference_no' => $request->reference_no,
                    'pr_worth' => $request->pr_worth,
                    'deposit' => $request->deposit,
                    'tax' => $request->tax,
                    'transit' => $request->transit,
                    'others' => $request->others,
                    'currency_id' => $default_afn,
                    'total_price' => $request->total_price,
                ];
                ProData::create($proData);
            }

            // Create Pro Items Record for selected Items
            ProItem::where('project_id', $id)->delete();
            foreach ($request->item as $key => $item) {
                if (gettype($item['item_id']) != 'integer') {

                    $item = [
                        'unit_id' => $item['item_id']['uom_id']['id'],
                        'uom_equiv_id' => $item['item_id']['uom_equiv_id']['id'],
                        'item_id' => $item['item_id']['id'],
                        'project_id' => $project->id,
                        'operation_id' => $item['operation_id']['id'],
                        'ammount' => $item['ammount'],
                        'unit_price' => $item['unit_price'],
                        'equivalent' => $item['equivalent'],
                        'density' => $item['density'],
                        'total_price' => $item['total_price'],
                    ];
                    ProItem::create($item);
                }
            }

            if ($account = Account::where('ref_code', $project->id)
                ->where('type_id', config('app.contract_account_type'))
                ->latest()->first()
            ) {
                $projectFR = FinancialRecord::where('type_id', $project->id)
                    ->where('account_id', $account->id)
                    ->where('type', 'project')
                    ->first();
                $data = [
                    'credit' => $request->pr_worth,
                    'debit' => 0,
                    'ex_rate_id' => ExchangeRate::latest()->first()->id,
                ];
                $projectFR->update($data);
            }

            DB::commit();
            // Helper::projectNotif10($project['id']);
            // Helper::projectNotif11($project['id']);
            return $project;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        DB::beginTransaction();
        try {
            ProData::where('project_id', $project->id)->delete();
            ProItem::where('project_id', $project->id)->delete();
            $account = Account::where('ref_code', $project->id)
                ->where('type_id', config('app.contract_account_type'))
                ->latest()->first();
            FinancialRecord::where('type_id', $project->id)
                ->where('type', 'project')
                ->delete();
            Helper::removeNotif($project->id, 'project');
            $project->delete();
            $account->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    public function latest()
    {
        return Project::withTrashed()->max('s_number') + 1;
    }
    public function latestProject()
    {
        return Project::with([
            'pro_data',
            'pro_items'
        ])
            ->whereHas('pro_data', function ($query) {
                return $query->where('project_id', '!=', null);
            })
            ->latest()->limit(3)->get();
    }
    public function activeProject()
    {
        return Project::with([
            'pro_data',
            'pro_items'
        ])
            ->whereHas('pro_data', function ($query) {
                return $query->where('project_id', '!=', null);
            })
            ->where('status', 'A')->latest()->limit(3)->get();
    }
    public function projectSales($id)
    {
        $sales1 = Sale::join('sales_ones AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.project_id, s.total, s.deposit, s.tax, s.service_cost, s.serial_no")
            ->where('project_id', $id);

        $sales3 = Sale::join('sales_threes AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.project_id, s.total, s.deposit, s.tax, s.service_cost, s.serial_no")
            ->where('project_id', $id);
        // $data[] = $sales1->union($sales3)->get();
        $data['deposit'] = $sales1->union($sales3)->sum('deposit');
        $data['total'] = $sales1->union($sales3)->sum('total');
        $data['service_cost'] = $sales1->union($sales3)->sum('service_cost');
        return $data;
    }


    public function projectTypeChart($id)
    {
        $sales1 = Sale::join('sales_ones AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.project_id")->where('project_id', $id);

        $sales3 = Sale::join('sales_threes AS s', 'sales.id', '=', 's.sales_id')
            ->selectRaw("s.sales_id, s.project_id")->where('project_id', $id);
        $matched_sales = $sales1->union($sales3)->pluck('sales_id');
        $stocks = StockRecord::with(['measur_unit', 'item'])->where('type', 'sale')->whereIn('type_id', $matched_sales)->get();
        $chart_data = [];
        // return $stocks;
        foreach ($stocks as $key => $value) {
            // $chart_data[$value['item']['name']] = [$value['uom_equiv_id'], $value['increment_equiv']];
            if (isset($chart_data[$value['measur_unit']['title']])) {
                $chart_data[$value['measur_unit']['title']] += $value['increment_equiv'];
            } else {
                $chart_data[$value['measur_unit']['title']] = $value['increment_equiv'];
            }
        }
        return $chart_data;
    }


    public function projectItemChart($id)
    {

        $data = Project::with([
            'pro_items.item_id',
            'pro_items.item_id.uom_equiv_id',
            'pro_items.item_id.uom_id',
        ])->latest()->find($id);

        $chart_data = [];
        foreach ($data['pro_items'] as $key => $value) {
            if (array_key_exists($value['item']['name'], $chart_data)) {
                $chart_data[$value['item']['name']] += $value['total_price'];
            } else {
                $chart_data[$value['item']['name']] = $value['total_price'];
            }
        }
        return $chart_data;
    }
}
