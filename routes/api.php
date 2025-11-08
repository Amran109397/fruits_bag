<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChartOfAccountsController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\FiscalYearController;  
use App\Http\Controllers\AccountingPeriodController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Customer & Vendor
Route::apiResource('customers', CustomerController::class);
Route::apiResource('vendors', VendorController::class);

// Dashboard

Route::get('dashboard', [DashboardController::class, 'summary']);
Route::get('dashboard/summary', [DashboardController::class, 'summary']);


// Sales
    Route::prefix('sales')->group(function () {
    Route::get('/invoices', [SalesController::class, 'index']);
    Route::post('/invoices', [SalesController::class, 'store']);
    Route::post('/invoices/{id}/post', [SalesController::class, 'post']);
    Route::post('/invoices/{id}/unpost', [SalesController::class, 'unpost']);
    Route::post('/receipts', [SalesController::class, 'receivePayment']); 
});

// Purchase
Route::post('purchase/bills/{id}/post', [PurchaseController::class, 'post']);
Route::apiResource('purchase/bills', PurchaseController::class);
Route::post('purchase/bills/{id}/unpost', [PurchaseController::class, 'unpost']);
Route::post('purchase/bills/{id}/unpay', [PurchaseController::class, 'unpay']);
Route::post('purchase/payments', [PurchaseController::class, 'pay']);






// Bank
Route::get('bank/accounts', [BankController::class, 'accountsIndex']);
Route::post('bank/accounts', [BankController::class, 'accountsStore']);
Route::put('bank/accounts/{id}', [BankController::class, 'accountsUpdate']);
Route::delete('bank/accounts/{id}', [BankController::class, 'accountsDelete']);


Route::get('bank/transactions', [BankController::class, 'transactionsIndex']);
Route::post('bank/transactions', [BankController::class, 'transactionsStore']);
Route::put('bank/transactions/{id}', [BankController::class, 'transactionsUpdate']);
Route::delete('bank/transactions/{id}', [BankController::class, 'transactionsDelete']);

// Reports
Route::get('reports/ar-aging', [ReportController::class, 'arAging']);
Route::get('reports/ap-aging', [ReportController::class, 'apAging']);


// Chart of Accounts
Route::apiResource('chart-of-accounts', ChartOfAccountsController::class);

// Journal Entries (Core Accounting)
Route::get('journal-entries', [JournalEntryController::class, 'index']);
Route::post('journal-entries', [JournalEntryController::class, 'store']);
Route::post('journal-entries/{id}/post', [JournalEntryController::class, 'post']);
Route::post('journal-entries/{id}/unpost', [JournalEntryController::class, 'unpost']);

Route::apiResource('fiscal-years', FiscalYearController::class);
Route::apiResource('accounting-periods', AccountingPeriodController::class);