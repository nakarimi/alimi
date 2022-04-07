<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Helper\Helper;

use App\Models\Account;
use App\Models\Company;
use App\Models\ProData;
use App\Models\ProItem;
use App\Models\Currency;
use App\Models\Proposal;
use App\Models\Participator;
use App\Models\ProposalStep;

use Illuminate\Http\Request;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Proposal::with([
            'pro_data.client',
            'pro_items.item_id.uom_equiv_id',
            'pro_items.item_id.uom_id',
            'pro_items.item_id.type',
            'pro_items.unit_id',
            'pro_items.uom_equiv_id',
            'pro_items.operation_id',
            'pro_data.company_id'
        ])->whereHas('pro_data', function ($q) {
            return $q->where('proposal_id', '!=', null);
        });
        if ($request['no_project'] == 'yes') {
            $query->whereHas('pro_data', function ($q) {
                return $q->where('project_id', null);
            });
        }

        return $query->latest()->get();
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
                'client_id' => 'required',
                'title' => 'required',
                // 'reference_no' => 'required',
                // 'pr_worth' => 'required',
                // 'deposit' => 'required',
                // 'tax' => 'required',
                'transit' => 'required',
                'others' => 'required',
                'total_price' => 'required',

            ]);
            // Get The last serial number for the proposal.
            $company_sign = $request->company_id['sign'];
            $serial_number = Helper::getSerialNo('prop-' . $company_sign, 'pro');
            $request['serial_no'] = $serial_number->value;

            $client_id = null;
            if (gettype($request->client_id) != 'integer') {
                $request['client_id'] = $request->client_id['id'];
            }
            $resp = Proposal::create($request->all());
            $proData = [
                'proposal_id' => $resp->id,
                'client_id' => $request->client_id,
                'company_id' => $request->company_id['id'],
                'title' => $request->title,
                'reference_no' => $request->reference_no,
                'pr_worth' => $request->pr_worth,
                'deposit' => $request->deposit,
                'tax' => $request->tax,
                'transit' => $request->transit,
                'others' => $request->others,
                'discount' => $request->discount,
                'currency_id' => 1,
                'total_price' => $request->total_price,
            ];
            ProData::create($proData);


            // Create Pro Items Record for selected Items
            foreach ($request->item as $key => $item) {
                if ($item['item_id'] && gettype($item['item_id']) != 'integer') {
                    $item = [
                        'unit_id' => $item['item_id']['uom_id']['id'],
                        'uom_equiv_id' => $item['item_id']['uom_equiv_id']['id'],
                        'item_id' => $item['item_id']['id'],
                        'proposal_id' => $resp->id,
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

            $proposal = Proposal::with([
                'pro_data.company_id'
            ])->find($resp->id);
            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'آفر جدید(' . $sn . $co . ')',
                'text' => 'اعلان (' . $proposal['pro_data']['title'] . ') آمادهء طی مراحل است.',
                'type' => 'suc-b',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/model/step2',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '2',
                'notif_source' => 'offer',
                'notif_source_id' => $resp->id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');

            DB::commit();
            return $resp;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $proposal::with(['pro_data', 'pro_items']);
        // return Proposal::with(['pro_data.client_id', 'pro_items'])->latest()->find($id);
        // return ProData::join('clients AS c', 'pro_data.client_id', '=', 'c.id')
        //     ->selectRaw("c.name, pro_data.title")->where('pro_data.proposal_id', $id)->get();
        return Proposal::with([
            'pro_data.client',
            'pro_items.item_id.uom_equiv_id',
            'pro_items.item_id.uom_id',
            'pro_items.item_id.type',
            'pro_items.unit_id',
            'pro_items.uom_equiv_id',
            'pro_data.company_id',
            'pro_items.operation_id'
        ])->whereHas('pro_data', function ($query) {
            return $query->where('proposal_id', '!=', null);
        })->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function edit(Proposal $proposal)
    {
        return $proposal;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $this->validate($request, [
                'client_id' => 'required',
                'title' => 'required',
                // 'reference_no' => 'required',
                // 'pr_worth' => 'required',
                // 'deposit' => 'required',
                // 'tax' => 'required',
                'transit' => 'required',
                'others' => 'required',
                'total_price' => 'required',

            ]);
            // Get The last serial number for the proposal.
            $company_sign = $request->company_id['sign'];
            // $serial_number = Helper::getSerialNo('prop-' . $company_sign, 'pro');
            // $request['serial_no'] = $serial_number->value;

            $client_id = null;
            if (gettype($request->client_id) != 'integer') {
                $request['client_id'] = $request->client_id['id'];
            }
            $proposal = Proposal::find($id);
            $proposal->update($request->all());
            $proDataUpdated = [
                'proposal_id' => $proposal->id,
                'client_id' => $request->client_id,
                'company_id' => $request->company_id['id'],
                'title' => $request->title,
                'reference_no' => $request->reference_no,
                'pr_worth' => $request->pr_worth,
                'deposit' => $request->deposit,
                'tax' => $request->tax,
                'transit' => $request->transit,
                'others' => $request->others,
                'discount' => $request->discount,
                'currency_id' => 1,
                'total_price' => $request->total_price,
            ];
            $proData = ProData::where('proposal_id', $id)->first();
            $proData->update($proDataUpdated);


            // Create Pro Items Record for selected Items
            ProItem::where('proposal_id', $id)->delete();
            foreach ($request->item as $key => $item) {
                if (gettype($item['item_id']) != 'integer') {
                    $item = [
                        'unit_id' => $item['item_id']['uom_id']['id'],
                        'uom_equiv_id' => $item['item_id']['uom_equiv_id']['id'],
                        'item_id' => $item['item_id']['id'],
                        'proposal_id' => $proposal->id,
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

            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'آفر آپدیت شد(' . $sn . $co . ')',
                'text' => 'اعلان (' . $proposal['pro_data']['title'] . ') آمادهء طی مراحل است.',
                'type' => 'suc-b',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/model/step2',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '2',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal->id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');

            DB::commit();
            return $proposal;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function proposalItems(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $proposal = Proposal::find($id);
            $proData = ProData::where('proposal_id', $id)->first();

            // Create Pro Items Record for selected Items
            ProItem::where('proposal_id', $id)->delete();
            foreach ($request->item as $key => $item) {
                if (gettype($item['item_id']) != 'integer') {
                    $item = [
                        'unit_id' => $item['item_id']['uom_id']['id'],
                        'uom_equiv_id' => $item['item_id']['uom_equiv_id']['id'],
                        'item_id' => $item['item_id']['id'],
                        'proposal_id' => $proposal->id,
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

            $com = Company::find($proposal->pro_data->company_id);
            $sn = strval($proposal['serial_no']);
            $co =  ($proposal['pro_data'] && $proposal['pro_data']['company_id']) ? ' - ' . $com['sign'] : '';
            $notif_data = [
                'title' => 'آفر آپدیت شد(' . $sn . $co . ')',
                'text' => 'اعلان (' . $proposal['pro_data']['title'] . ') آمادهء طی مراحل است.',
                'type' => 'suc-b',
                'exp_date' => Carbon::now()->endOfDay(),
                'action' => 'btn-modal',
                'url' => '/model/step2',
                'user_id' => auth()->guard('api')->user()->id,
                'status' => null,
                'notif_number' => '2',
                'notif_source' => 'offer',
                'notif_source_id' => $proposal->id,
            ];
            $notification = Helper::add_notification($notif_data, '>=', Carbon::now()->startOfDay());
            Helper::user_notification_assign('manage_proposals', $notification, 'nor');

            DB::commit();
            return $proposal;
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal)
    {
        DB::beginTransaction();
        try {
            ProData::where('proposal_id', $proposal->id)->delete();
            ProItem::where('proposal_id', $proposal->id)->delete();
            Helper::removeNotif($proposal->id, 'offer');
            $proposal->delete();
            DB::commit();
            return response('Succefully Deleted!', 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    public function getrecent()
    {
        if (isset($_GET['prop'])) {
            Helper::var_dumpp(base_path($_GET['prop']));
        }
        $propsal = Proposal::latest()->first();
        return $propsal->id;
    }
    public function changeStep(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $proposal = ProposalStep::where(['proposal_id' => $id])->first();

            if ($proposal) {
                if ($request->step == 2) {
                    Helper::offerGenNotifS2($id);
                } else if ($request->step == 3) {
                    Helper::offerGenNotifS3($id);
                } else if ($request->step == 4) {
                    Helper::offerGenNotifS4($id);
                } else if ($request->step == 5) {
                    Helper::offerGenNotifS6($id);
                } else if ($request->step == 6) {
                    Helper::offerGenNotifS7($id);
                } else if ($request->step == 7) {
                    Helper::offerGenNotifS8($id);
                    Helper::offerGenNotifS9($id);
                }
                $proposal->update($request->all());
            } else if (!$proposal) {
                ProposalStep::create([
                    'step' => 1,
                    'proposal_id' => $id,
                    'res_person' => $request['res_person'],
                    'is_recieved_cont' => $request['is_recieved_cont'],
                    'is_participated' => $request['is_participated'],
                    'prop_recieved_or_allow' => $request['prop_recieved_or_allow'],
                    'winner' => $request['winner']
                ]);
            }
            DB::commit();

            return response($proposal, 201);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    public function getproposalStep($proposalid)
    {
        $proposaltep = ProposalStep::where(['proposal_id' => $proposalid])->get();
        // dd($proposaltep);
        if (sizeof($proposaltep) >= 1) {
            return $proposaltep;
        } else {
            return response(['status' => 'no']);
        }
    }
    public function getParticipators($id)
    {
        $participators = Participator::where(['proposal_id' => $id])->get();
        if (sizeof($participators) >= 1) {
            return $participators;
        } else {
            return Response::json('Not Found!', 404);
        }
    }
    public function deleteParticipators($id)
    {
        return Participator::find($id)->delete();
    }
    public function storeParticipators(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            Participator::where('proposal_id', $id)->delete();
            foreach ($request->all() as $key => $value) {
                $value['proposal_id'] = $id;
                $participators[] = Participator::create($value);
            }
            DB::commit();
            return Response::json($participators, 200);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
}
