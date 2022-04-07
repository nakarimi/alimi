<?php
// DB::beginTransaction();
// try {
//     DB::commit();
//     return Response::json('Done', 200);;
// } catch (Exception $e) {
//     DB::rollback();
//     return Response::json($e, 400);
// }

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use App\Helper\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        return view('application');
    }

    public function trancate_db()
    {
        $tables = DB::select('SHOW TABLES');
        $ignore_tb = [
            "role_has_permissions", "roles", "permissions", "model_has_permissions",
            "model_has_roles", 'oauth_refresh_tokens', 'oauth_personal_access_clients',
            'oauth_clients', 'oauth_auth_codes', 'oauth_access_tokens',
            'migrations', 'companies', 'account_types', 'accounts', 'currencies',
            'exchange_rates', 'item_types', 'measurment_units', 'operations', 'users'
        ];
        $clean_tables = [];
        // it do truncate all tables in database
        Schema::disableForeignKeyConstraints();
        foreach ($tables as $key => $table) {
            $table = reset($table);
            if (in_array($table, $ignore_tb)) {
                continue;
            } else {
                $clean_tables[] = $table;
            }
            DB::table($table)->truncate();
        }
        Schema::enableForeignKeyConstraints();
        return $clean_tables;
    }
}
