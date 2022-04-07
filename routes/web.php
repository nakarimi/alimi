<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/print', 'PrinterController@print')->name('printer');
Route::get('/project/print', 'PrinterController@printProject')->name('printerp');
Route::get('/proposal/print', 'PrinterController@printProposal')->name('printerpp');
Route::get('/reports/sale', 'ReportsController@saleReport')->name('saleReport');
Route::get('/reports/expense', 'ReportsController@expenseReport')->name('expenseReport');
Route::get('/reports/benefit', 'ReportsController@benefitReport')->name('benefitReport');
Route::get('/reports/accounts', 'ReportsController@accountsReport')->name('accountsReport');
Route::get('/reports/account_details', 'ReportsController@account_detailsReport')->name('account_detailsReport');
Route::get('/reports/taraz', 'ReportsController@tarazReport')->name('tarazReport');

Route::get('/{any}', 'ApplicationController')->where('any', '.*');

