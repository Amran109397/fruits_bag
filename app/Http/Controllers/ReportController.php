<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * 📊 Accounts Receivable Aging (Customer-based)
     * - Calculates outstanding amounts (total - paid_amount)
     * - Uses due_date if available, otherwise falls back to invoice date
     * - Shows only unpaid or partially paid invoices
     */
    public function arAging()
    {
        $data = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select(
                'customers.name',
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(invoices.due_date, invoices.date)) <= 30
                            THEN (invoices.total - invoices.paid_amount)
                            ELSE 0
                        END
                    ) AS due_0_30
                '),
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(invoices.due_date, invoices.date)) BETWEEN 31 AND 60
                            THEN (invoices.total - invoices.paid_amount)
                            ELSE 0
                        END
                    ) AS due_31_60
                '),
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(invoices.due_date, invoices.date)) > 60
                            THEN (invoices.total - invoices.paid_amount)
                            ELSE 0
                        END
                    ) AS due_over_60
                ')
            )
            ->whereRaw('(invoices.total - invoices.paid_amount) > 0')
            ->groupBy('customers.name')
            ->get();

        return response()->json($data);
    }

    /**
     * 💰 Accounts Payable Aging (Vendor-based)
     * - Calculates outstanding amounts (total - paid_amount)
     * - Uses due_date if available, otherwise falls back to bill date
     * - Shows only unpaid or partially paid bills
     */
    public function apAging()
    {
        $data = DB::table('bills')
            ->join('vendors', 'bills.vendor_id', '=', 'vendors.id')
            ->select(
                'vendors.name',
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(bills.due_date, bills.date)) <= 30
                            THEN (bills.total - bills.paid_amount)
                            ELSE 0
                        END
                    ) AS due_0_30
                '),
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(bills.due_date, bills.date)) BETWEEN 31 AND 60
                            THEN (bills.total - bills.paid_amount)
                            ELSE 0
                        END
                    ) AS due_31_60
                '),
                DB::raw('
                    SUM(
                        CASE
                            WHEN DATEDIFF(NOW(), COALESCE(bills.due_date, bills.date)) > 60
                            THEN (bills.total - bills.paid_amount)
                            ELSE 0
                        END
                    ) AS due_over_60
                ')
            )
            ->whereRaw('(bills.total - bills.paid_amount) > 0')
            ->groupBy('vendors.name')
            ->get();

        return response()->json($data);
    }
}
