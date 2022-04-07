<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Clear cache and redirect to the home page.
Route::get('/cc', function () {
  $exitCode = Artisan::call('config:cache');
  $exitCode = Cache::flush();
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  return redirect($actual_link);
});

Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
Route::get('user', 'UserController@user')->middleware('auth:api');
Route::resource('users', 'UserController');
Route::resource('permissions', 'PermissionController');
Route::get('createpermissions', 'PermissionController@createPermissions');
Route::get('users/privilage/{id}', 'PermissionController@userPrivileges');
Route::resource('itemtype', 'ItemTypeController');
Route::resource('item', 'ItemController');
Route::resource('uom', 'MeasurmentUnitController');
Route::resource('storage', 'StorageController');
Route::get('totalstorage', 'StorageController@totalstorage');

Route::get('totalFGGT', 'StorageController@alltotals');

Route::resource('fuelstation', 'FuelStationController');
Route::get('latestfuelstation', 'FuelStationController@latest');
Route::resource('fuelstorestation', 'FuelStationStorageController');
Route::post('users1', 'UserController@store');
Route::post('users1', 'UserController@store');
Route::get('tables_trancate', 'ApplicationController@trancate_db');

// Sales
Route::resource('sale1', 'SaleOneController');
Route::resource('sale2', 'SaleTwoController');
Route::resource('sale3', 'SaleThreeController');
Route::resource('sale4', 'SaleFourController');
Route::get('sales', 'SaleOneController@allSales');
Route::get('sales/{id}', 'SaleOneController@show');
Route::get('sale/{id}', 'SaleOneController@showSale');
Route::delete('sales/{sale}', 'SaleOneController@deleting');

// Product Review
Route::resource('project', 'ProjectController');
Route::post('projectstchange/{id}', 'ProjectController@changeStep');
Route::get('projectstep/{id}', 'ProjectController@getprojectStep');
Route::get('proposalstep/{id}', 'ProposalController@getproposalStep');
Route::get('participators/{id}', 'ProposalController@getParticipators');
Route::post('participators/{id}', 'ProposalController@storeParticipators');
Route::get('participator/{id}', 'ProposalController@deleteParticipators');
Route::post('proposlstchange/{id}', 'ProposalController@changeStep');
Route::get('procchange/{id}/{stid}', 'PurchaseController@changeStep');
Route::post('expense_confirm/{id}', 'ExpensesController@expenseConfirm');
Route::post('transaction_confirm/{id}', 'TransactionController@transactionConfirm');
Route::post('tranchange/{id}', 'TransactionController@changeStep');
Route::get('tranfchange/{id}/{stid}', 'TransferController@changeStep');
Route::resource('proposal', 'ProposalController');
Route::patch('proposal-items/{id}', 'ProposalController@proposalItems');
Route::get('getrecent', 'ProposalController@getrecent');
Route::resource('currency', 'CurrencyController');
Route::resource('operation', 'OperationController');
Route::resource('acount_type', 'AccountTypeController');
Route::get('account_type', 'AccountTypeController@accountType');
Route::get('accounts', 'AccountTypeController@allAccounts');
Route::resource('clients', 'ClientController');
Route::resource('item-type', 'ItemTypeController');
Route::resource('items', 'ItemController');
Route::resource('m-units', 'MeasurmentUnitController');
Route::resource('pro-item', 'ProItemController');
Route::resource('account', 'AccountController');
Route::resource('transfer', 'TransferController');
Route::resource('notification', 'NotificationController');

Route::resource('godam', 'InventoryController');
Route::get('firstgodam', 'InventoryController@first');

Route::resource('financial', 'FinancialRecordController');
Route::resource('despenser', 'FuelDespenserController');

// Latest Records
// Route::get('announce-last','ProposalController@latest');
Route::get('project-last', 'ProjectController@latest');
Route::resource('companies', 'CompanyController');
Route::post('currency/rates', 'CurrencyController@rates');
Route::get('serial-num', 'SerialNumberController@latest');
Route::post('financial-account', 'FinancialRecordController@byaccount');
Route::post('storage-station', 'FuelStationStorageController@bystation');

Route::get('lastest-projects', 'ProjectController@latestProject');
Route::get('active-project', 'ProjectController@activeProject');
Route::get('client/{id}', 'ProjectController@getClient');

//vendors
Route::resource('vendors', 'VendorController');
Route::resource('procurments', 'PurchaseController');
Route::get('purchSerialNO', 'PurchaseController@serial');
Route::get('sources', 'StorageController@allTypeSource');
Route::get('sources/find-items', 'StorageController@allItemsOfSource');

//Archive
Route::get('accoutload', 'AccountController@getaccount');
//transactions
Route::resource('transaction', 'TransactionController');
Route::get('transactionSerialnum', 'TransactionController@serial');

//Expenses
Route::resource('expenses', 'ExpensesController');
Route::get('exserialno', 'ExpensesController@serial');
Route::get('item-records', 'StockRecordController@itemRecords');
Route::get('bank-accounts', 'AccountController@bankAccounts');

// Archive
Route::resource('archive', 'ArchiveController');
Route::post('archive/upload', 'ArchiveController@upload');
Route::post('archive/remove-file', 'ArchiveController@removeFile');
Route::get('archive/download-file/{path}', 'ArchiveController@downloadFile');
Route::get('archive/download-file/{path}/{c}', 'ArchiveController@downloadFile');

Route::get('mostresent', 'ArchiveController@mostresent');
Route::get('sale/project/{project_id}', 'ProjectController@projectSales');
Route::get('sale/project/type/{project_id}', 'ProjectController@projectTypeChart');
Route::get('sale/project/item-chart/{project_id}', 'ProjectController@projectItemChart');
// Route::get('transaction/view/{transaction_id}', 'TransactionController@');


// Graphes Data
Route::get('graphs/purchase', 'GraphsController@purchase');
Route::get('graphs/sale-value', 'GraphsController@saleValue');
Route::get('graphs/sale-last-month-g', 'GraphsController@saleLastMonthG');
Route::get('graphs/allsalecount', 'GraphsController@allSaleCount');
Route::get('graphs/all-items-sales-price', 'GraphsController@allItemsSalesPrice');
Route::get('graphs/storage_graph', 'GraphsController@storageGraph');
Route::get('graphs/storage_items_graph', 'GraphsController@storageItemsGraph');
Route::get('graphs/contracts-graphs', 'GraphsController@contractsGraphs');
Route::get('graphs/pro-prop', 'GraphsController@proPropGraphs');

// Notifications
Route::post('notif/pin', 'UserNotificationController@pin');
Route::post('notif/no-important', 'UserNotificationController@noimportant');
Route::post('notif/remove-notif-user', 'UserNotificationController@removeNotifUser');

Route::post('m16', 'SaleOneController@m16Store');
Route::post('sale_check_list', 'SaleOneController@saleCheckList');
Route::post('sale_info_confirmation/{type}/{id}', 'SaleOneController@saleInfoConfirmation');
Route::post('s2costform', 'SaleOneController@s2CostFormUpdate');
Route::post('purchase_service_cost/{id}', 'PurchaseController@purchaseServiceCost');
Route::get('all-in-one', 'ReportsController@allInOne');