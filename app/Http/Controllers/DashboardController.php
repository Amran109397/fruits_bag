<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        return response()->json([
            'total_customers' => DB::table('customers')->count(),
            'total_vendors'   => DB::table('vendors')->count(),
            'total_invoices'  => DB::table('invoices')->count(),
            'total_bills'     => DB::table('bills')->count(),
            'total_sales'     => DB::table('invoices')->sum('total'),
            'total_purchases' => DB::table('bills')->sum('total'),
            'bank_balance'    => DB::table('bank_accounts')->sum('current_balance'),
        ]);
    }
}
