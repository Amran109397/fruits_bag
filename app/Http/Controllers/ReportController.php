<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Accounts Receivable Aging (Customer-based)
    public function arAging()
    {
        $data = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select(
                'customers.name',
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), invoices.due_date) <= 30 THEN invoices.total ELSE 0 END) as due_0_30'),
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), invoices.due_date) BETWEEN 31 AND 60 THEN invoices.total ELSE 0 END) as due_31_60'),
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), invoices.due_date) > 60 THEN invoices.total ELSE 0 END) as due_over_60')
            )
            ->groupBy('customers.name')
            ->get();

        return response()->json($data);
    }

    // Accounts Payable Aging (Vendor-based)
    public function apAging()
    {
        $data = DB::table('bills')
            ->join('vendors', 'bills.vendor_id', '=', 'vendors.id')
            ->select(
                'vendors.name',
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), bills.due_date) <= 30 THEN bills.total ELSE 0 END) as due_0_30'),
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), bills.due_date) BETWEEN 31 AND 60 THEN bills.total ELSE 0 END) as due_31_60'),
                DB::raw('SUM(CASE WHEN DATEDIFF(NOW(), bills.due_date) > 60 THEN bills.total ELSE 0 END) as due_over_60')
            )
            ->groupBy('vendors.name')
            ->get();

        return response()->json($data);
    }
}
