<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * 📊 Dashboard Summary API
     * Returns overall totals for Sales, Purchases, Bank, Receivable, Payable, etc.
     */
    public function summary()
    {
        // ---- SALES & PURCHASE TOTALS ----
        $totalSales = DB::table('invoices')->sum('total');
        $totalPurchases = DB::table('bills')->sum('total');

        // ---- BANK BALANCE ----
        $bankBalance = DB::table('bank_accounts')->sum('current_balance');

        // ---- ACCOUNTS RECEIVABLE (Unpaid Customer Invoices) ----
        $receivableDue = DB::table('invoices')
            ->whereRaw('(total - paid_amount) > 0')
            ->sum(DB::raw('(total - paid_amount)'));

        // ---- ACCOUNTS PAYABLE (Unpaid Vendor Bills) ----
        $payableDue = DB::table('bills')
            ->whereRaw('(total - paid_amount) > 0')
            ->sum(DB::raw('(total - paid_amount)'));

        // ---- TOTAL CUSTOMERS & VENDORS ----
        $totalCustomers = DB::table('customers')->count();
        $totalVendors = DB::table('vendors')->count();

        // ---- OPTIONAL: JOURNAL BALANCE (if needed) ----
        $journalCount = DB::table('journal_entries')->count();

        // ---- RESPONSE ----
        return response()->json([
            'total_sales' => round($totalSales, 2),
            'total_purchases' => round($totalPurchases, 2),
            'bank_balance' => round($bankBalance, 2),
            'ar_due' => round($receivableDue, 2),
            'ap_due' => round($payableDue, 2),
            'total_customers' => $totalCustomers,
            'total_vendors' => $totalVendors,
            'journal_entries' => $journalCount,
        ]);
    }
}
