<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Helper\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matchedPermissions = [];
        $systemPrivileges = Permission::where('p_id', null)->get();
        if ($request['id']) {
            $userPrivilegesId = $this->userPrivileges($request['id'])->pluck('id')->toArray();
        }else{
            $userPrivilegesId = Permission::get()->pluck('id')->toArray();
        }
        foreach ($systemPrivileges as $key => &$sPri) {
            $x = [];
            if ($request['id'] && in_array($sPri->id, $userPrivilegesId)) {
                $sPri['assign'] = true;
                $childPrivileges = Permission::where('p_id', $sPri->id)->get();
                foreach ($childPrivileges as $key => $nPri) {
                    $x[$key] = $nPri;
                    if (in_array($nPri->id, $userPrivilegesId)) {
                        $x[$key]['assign'] = true;
                    } else {
                        $x[$key]['assign'] = false;
                    }
                }
            } else {
                $sPri['assign'] = false;
                $childPrivileges = Permission::where('p_id', $sPri->id)->get();
                foreach ($childPrivileges as $key => $nPri) {
                    $x[$key] = $nPri;
                    if (in_array($nPri->id, $userPrivilegesId)) {
                        $x[$key]['assign'] = false;
                    } else {
                        $x[$key]['assign'] = false;
                    }
                }
            }
            $sPri['can_do'] = $x;
            $matchedPermissions[] = $sPri;
        }
        return $matchedPermissions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userPrivileges($id)
    {
        $user = User::find($id);
        $permissions = $user->permissions;
        return $permissions;
    }
    public function userPermissionAssign(Request $request)
    {

        $user = User::find(3);
        $user->givePermissionTo([]);
    }

    public function createPermissions()
    {
        DB::beginTransaction();
        try {
            Schema::disableForeignKeyConstraints();
            Artisan::call('cache:forget spatie.permission.cache');
            Artisan::call('cache:clear');
            DB::table('permissions')->truncate();
            // $permission = Permission::create(['name' => '', 'display_name' => 'test']);
            $privilages = [
                ['name' => 'main_dashboard', 'display_name' => 'داشبورد'],
                ['name' => 'dashboard_notifications', 'display_name' => 'یادآوری ها'],
                [
                    'name' => 'manage_reports', 'display_name' => 'مدیریت راپورها', 'can_do' => [
                        // ['name' => 'reports_view', 'display_name' => 'مشاهده راپور'],
                    ]
                ],
                [
                    'name' => 'manage_fuel', 'display_name' => 'مدیریت تانک تیل ها', 'can_do' => [
                        ['name' => 'fuel_view', 'display_name' => 'مشاهده تانک تیل'],
                        ['name' => 'fuel_add', 'display_name' => 'افزودن تانک تیل'],
                        ['name' => 'fuel_edit', 'display_name' => 'ویرایش تانک تیل'],
                        ['name' => 'fuel_remove', 'display_name' => 'حذف تانک تیل'],
                    ]
                ],
                [
                    'name' => 'manage_products', 'display_name' => 'مدیریت اجناس و محصولات', 'can_do' => [
                        ['name' => 'product_view', 'display_name' => 'مشاهده اجناس و محصولات'],
                        ['name' => 'product_add', 'display_name' => 'افزودن اجناس و محصولات'],
                        ['name' => 'product_edit', 'display_name' => 'ویرایش اجناس و محصولات'],
                        ['name' => 'product_remove', 'display_name' => 'حذف اجناس و محصولات'],
                    ]
                ],
                [
                    'name' => 'manage_godams', 'display_name' => 'مدیریت گدام ها', 'can_do' => [
                        ['name' => 'godam_view', 'display_name' => 'مشاهده گدام'],
                        ['name' => 'godam_add', 'display_name' => 'افزودن گدام'],
                        ['name' => 'godam_edit', 'display_name' => 'ویرایش گدام'],
                        ['name' => 'godam_remove', 'display_name' => 'حذف گدام'],
                    ]
                ],
                [
                    'name' => 'manage_transfers', 'display_name' => 'مدیریت انتقالات', 'can_do' => [
                        ['name' => 'transfer_view', 'display_name' => 'مشاهده انتقال'],
                        ['name' => 'transfer_add', 'display_name' => 'افزودن انتقال'],
                        ['name' => 'transfer_edit', 'display_name' => 'ویرایش انتقال'],
                        ['name' => 'transfer_remove', 'display_name' => 'حذف انتقال'],
                    ]
                ],
                [
                    'name' => 'manage_expenses', 'display_name' => 'مدیریت مصارف', 'can_do' => [
                        ['name' => 'expense_view', 'display_name' => 'مشاهده مصرف'],
                        ['name' => 'expense_add', 'display_name' => 'افزودن مصرف'],
                        ['name' => 'expense_edit', 'display_name' => 'ویرایش مصرف'],
                        ['name' => 'expense_remove', 'display_name' => 'حذف مصرف'],
                        ['name' => 'expense_steps', 'display_name' => 'مدیریت مراحل مصرف'],
                    ]
                ],
                [
                    'name' => 'manage_users', 'display_name' => 'مدیریت کاربران', 'can_do' => [
                        ['name' => 'user_view', 'display_name' => 'مشاهده کاربر'],
                        ['name' => 'user_add', 'display_name' => 'افزودن کاربر'],
                        ['name' => 'user_edit', 'display_name' => 'ویرایش کاربر'],
                        ['name' => 'user_remove', 'display_name' => 'حذف کاربر'],
                    ]
                ],
                [
                    'name' => 'manage_contracts', 'display_name' => 'مدیریت قراردادها', 'can_do' => [
                        ['name' => 'contract_view', 'display_name' => 'مشاهده قرارداد'],
                        ['name' => 'contract_add', 'display_name' => 'افزودن قرارداد'],
                        ['name' => 'contract_edit', 'display_name' => 'ویرایش قرارداد'],
                        ['name' => 'contract_remove', 'display_name' => 'حذف قرارداد'],
                        ['name' => 'contract_steps', 'display_name' => 'مدیریت مراحل قرارداد'],
                    ]
                ],
                [
                    'name' => 'manage_proposals', 'display_name' => 'مدیریت آفرها', 'can_do' => [
                        ['name' => 'proposal_view', 'display_name' => 'مشاهده آفر'],
                        ['name' => 'proposal_add', 'display_name' => 'افزودن آفر'],
                        ['name' => 'proposal_edit', 'display_name' => 'ویرایش آفر'],
                        ['name' => 'proposal_remove', 'display_name' => 'حذف آفر'],
                        ['name' => 'proposal_steps', 'display_name' => 'مدیریت مراحل آفر'],
                    ]
                ],
                [
                    'name' => 'manage_accounts', 'display_name' => 'مدیریت حساب ها', 'can_do' => [
                        ['name' => 'account_view', 'display_name' => 'مشاهده حساب'],
                        ['name' => 'account_add', 'display_name' => 'افزودن حساب'],
                        ['name' => 'account_edit', 'display_name' => 'ویرایش حساب'],
                        ['name' => 'account_remove', 'display_name' => 'حذف حساب'],
                    ]
                ],
                [
                    'name' => 'manage_transactions', 'display_name' => 'مدیریت معاملات تجارتی', 'can_do' => [
                        ['name' => 'transaction_view', 'display_name' => 'مشاهده معامله تجارتی'],
                        ['name' => 'transaction_add', 'display_name' => 'افزودن معامله تجارتی'],
                        ['name' => 'transaction_edit', 'display_name' => 'ویرایش معامله تجارتی'],
                        ['name' => 'transaction_remove', 'display_name' => 'حذف معامله تجارتی'],
                        ['name' => 'transaction_steps', 'display_name' => 'مدیریت مراحل معامله تجارتی'],
                    ]
                ],
                [
                    'name' => 'manage_storages', 'display_name' => 'مدیریت ذخایر', 'can_do' => [
                        ['name' => 'storage_view', 'display_name' => 'مشاهده ذخیره'],
                        ['name' => 'storage_add', 'display_name' => 'افزودن ذخیره'],
                        ['name' => 'storage_edit', 'display_name' => 'ویرایش ذخیره'],
                        ['name' => 'storage_remove', 'display_name' => 'حذف ذخیره'],
                    ]
                ],
                [
                    'name' => 'manage_purchases', 'display_name' => 'مدیریت خریداری ها', 'can_do' => [
                        ['name' => 'purchase_view', 'display_name' => 'مشاهده خرید'],
                        ['name' => 'purchase_add', 'display_name' => 'افزودن خرید'],
                        ['name' => 'purchase_edit', 'display_name' => 'ویرایش خرید'],
                        ['name' => 'purchase_remove', 'display_name' => 'حذف خرید'],
                        ['name' => 'purchase_steps', 'display_name' => 'مدیریت مراحل خرید'],
                    ]
                ],
                [
                    'name' => 'manage_archives', 'display_name' => 'مدیریت آرشیف', 'can_do' => [
                        ['name' => 'archive_view', 'display_name' => 'مشاهده آرشیف'],
                        ['name' => 'archive_add', 'display_name' => 'افزودن آرشیف'],
                        ['name' => 'archive_remove', 'display_name' => 'حذف آرشیف'],
                    ]
                ],
                [
                    'name' => 'manage_clients', 'display_name' => 'تنظیمات نهاد', 'can_do' => [
                        ['name' => 'client_view', 'display_name' => 'مشاهده نهاد'],
                        ['name' => 'client_add', 'display_name' => 'افزودن نهاد'],
                        ['name' => 'client_edit', 'display_name' => 'ویرایش نهاد'],
                        ['name' => 'client_remove', 'display_name' => 'حذف نهاد'],
                    ]
                ],
                [
                    'name' => 'manage_vendors', 'display_name' => 'تنظیمات فروشندگان', 'can_do' => [
                        ['name' => 'vendor_view', 'display_name' => 'مشاهده فروشندگان'],
                        ['name' => 'vendor_add', 'display_name' => 'افزودن فروشندگان'],
                        ['name' => 'vendor_edit', 'display_name' => 'ویرایش فروشندگان'],
                        ['name' => 'vendor_remove', 'display_name' => 'حذف فروشندگان'],
                    ]
                ],
                [
                    'name' => 'manage_settings', 'display_name' => 'تنظیمات سیستم', 'can_do' => [
                        ['name' => 'setting_currency', 'display_name' => 'نرخ اسعار'],
                        ['name' => 'setting_acc_type_add', 'display_name' => 'افزودن نوعیت حساب'],
                        ['name' => 'setting_acc_type_edit', 'display_name' => 'ویرایش نوعیت حساب'],
                        ['name' => 'setting_acc_type_remove', 'display_name' => 'حذف نوعیت حساب'],
                        ['name' => 'setting_item_type_add', 'display_name' => 'افزودن نوعیت محصولات'],
                        ['name' => 'setting_item_type_edit', 'display_name' => 'ویرایش نوعیت محصولات'],
                        ['name' => 'setting_item_type_remove', 'display_name' => 'حذف نوعیت محصولات'],
                        ['name' => 'setting_uom_add', 'display_name' => 'افزودن واحدات اندازه گیری'],
                        ['name' => 'setting_uom_edit', 'display_name' => 'ویرایش واحدات اندازه گیری'],
                        ['name' => 'setting_uom_remove', 'display_name' => 'حذف واحدات اندازه گیری'],
                        ['name' => 'setting_operations', 'display_name' => 'عملیه های اساسی'],
                    ]
                ],
                [
                    'name' => 'manage_sales1', 'display_name' => 'مدیریت فروشات عمده قراردادی', 'can_do' => [
                        ['name' => 'sales1_view', 'display_name' => 'مشاهده فروش عمده قراردادی'],
                        ['name' => 'sales1_add', 'display_name' => 'افزودن فروش عمده قراردادی'],
                        ['name' => 'sales1_edit', 'display_name' => 'ویرایش فروش عمده قراردادی'],
                        ['name' => 'sales1_remove', 'display_name' => 'حذف فروش عمده قراردادی'],
                        ['name' => 'sales1_steps', 'display_name' => 'مدیریت مراحل فروش عمده قراردادی'],
                    ]
                ],
                [
                    'name' => 'manage_sales2', 'display_name' => 'مدیریت فروشات عمده عادی', 'can_do' => [
                        ['name' => 'sales2_view', 'display_name' => 'مشاهده فروش عمده عادی'],
                        ['name' => 'sales2_add', 'display_name' => 'افزودن فروش عمده عادی'],
                        ['name' => 'sales2_edit', 'display_name' => 'ویرایش فروش عمده عادی'],
                        ['name' => 'sales2_remove', 'display_name' => 'حذف فروش عمده عادی'],
                        ['name' => 'sales2_steps', 'display_name' => 'مدیریت مراحل فروش عمده عادی'],
                    ]
                ],
                [
                    'name' => 'manage_sales3', 'display_name' => 'مدیریت فروشات پرچون قراردادی', 'can_do' => [

                        ['name' => 'sales3_view', 'display_name' => 'مشاهده فروش پرچون قراردادی'],
                        ['name' => 'sales3_add', 'display_name' => 'افزودن فروش پرچون قراردادی'],
                        ['name' => 'sales3_edit', 'display_name' => 'ویرایش فروش پرچون قراردادی'],
                        ['name' => 'sales3_remove', 'display_name' => 'حذف فروش پرچون قراردادی'],
                        ['name' => 'sales3_steps', 'display_name' => 'مدیریت مراحل فروش پرچون قراردادی'],
                    ]
                ],
                [
                    'name' => 'manage_sales4', 'display_name' => 'مدیریت فروشات پرچون عادی', 'can_do' => [

                        ['name' => 'sales4_view', 'display_name' => 'مشاهده فروش پرچون عادی'],
                        ['name' => 'sales4_add', 'display_name' => 'افزودن فروش پرچون عادی'],
                        ['name' => 'sales4_edit', 'display_name' => 'ویرایش فروش پرچون عادی'],
                        ['name' => 'sales4_remove', 'display_name' => 'حذف فروش پرچون عادی'],
                        ['name' => 'sales4_steps', 'display_name' => 'مدیریت مراحل فروش پرچون عادی'],
                    ]
                ],
                [
                    'name' => 'manage_graphes', 'display_name' => 'بررسی گراف ها', 'can_do' => []
                ],
                [
                    'name' => 'steps_confirmation_allow', 'display_name' => 'تاییدی مراحل', 'can_do' => []
                ]
            ];
            foreach ($privilages as $value) {
                Permission::create($value);
            }

            foreach ($privilages as $key => $value) {
                if (array_key_exists('can_do', $value)) {
                    foreach ($value['can_do'] as $can) {
                        $can['p_id'] = $key + 1;
                        Permission::create($can);
                    }
                }
            }
            $user = User::first();
            $user->syncPermissions();
            $ids = Permission::get()->pluck('id')->toArray();
            $user->givePermissionTo($ids);

            Schema::enableForeignKeyConstraints();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find($request[0]);
        $user->syncPermissions();
        $userPrivilegesId = array_column($request[1], 'id');
        $systemPrivileges = Permission::where('p_id', null)->get();
        foreach ($systemPrivileges as $key => &$sPri) {
            if (in_array($sPri->id, $userPrivilegesId)) {
                $user->givePermissionTo($sPri->id);
                foreach ($request[1] as $key => $privilage) {
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
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $serialNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $serialNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $serialNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $serialNumber)
    {
        //
    }

    public function latest(Request $request)
    {
        if ($resp = Permission::where('type', $request->type)->latest()->first()) {
            return $resp->value + 1;
        } else {
            return 101;
        }
    }
}
