<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BankController;
    

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
Route::get('dashboard/summary', [DashboardController::class, 'summary']);

// Sales
Route::get('sales/invoices', [SalesController::class, 'index']);
Route::post('sales/invoices', [SalesController::class, 'store']);
Route::post('sales/invoices/{id}/post', [SalesController::class, 'post']);
Route::post('sales/receipts', [SalesController::class, 'receive']);

// Purchase
Route::get('purchase/bills', [PurchaseController::class, 'index']);
Route::post('purchase/bills', [PurchaseController::class, 'store']);
Route::post('purchase/bills/{id}/post', [PurchaseController::class, 'post']);
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