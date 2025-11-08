<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
{
    return response()->json([
        'total_sales' => DB::table('invoices')->sum('total'),
        'total_purchases' => DB::table('bills')->sum('total'),
        'bank_balance' => DB::table('bank_accounts')->sum('current_balance'),
        'ar_due' => DB::table('invoices')->where('status', 'unpaid')->sum('total'),
        'ap_due' => DB::table('bills')->where('status', 'unpaid')->sum('total'),
        'total_customers' => DB::table('customers')->count(),
        'total_vendors' => DB::table('vendors')->count(),
    ]);
}

}
