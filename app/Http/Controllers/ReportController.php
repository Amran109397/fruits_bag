<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Accounts Receivable Aging
    public function arAging()
    {
        $data = DB::table('invoices')
            ->leftJoin('payments_received', 'invoices.id', '=', 'payments_received.invoice_id')
            ->select(
                'invoices.id',
                'invoices.invoice_number',
                'invoices.date',
                'invoices.due_date',
                'invoices.total',
                DB::raw('COALESCE(SUM(payments_received.amount),0) as paid'),
                DB::raw('(invoices.total - COALESCE(SUM(payments_received.amount),0)) as balance')
            )
            ->groupBy('invoices.id','invoices.invoice_number','invoices.date','invoices.due_date','invoices.total')
            ->havingRaw('(invoices.total - COALESCE(SUM(payments_received.amount),0)) > 0')
            ->get();

        return response()->json($data);
    }

    // Accounts Payable Aging
    public function apAging()
    {
        $data = DB::table('bills')
            ->leftJoin('payments_made', 'bills.id', '=', 'payments_made.bill_id')
            ->select(
                'bills.id',
                'bills.bill_number',
                'bills.date',
                'bills.due_date',
                'bills.total',
                DB::raw('COALESCE(SUM(payments_made.amount),0) as paid'),
                DB::raw('(bills.total - COALESCE(SUM(payments_made.amount),0)) as balance')
            )
            ->groupBy('bills.id','bills.bill_number','bills.date','bills.due_date','bills.total')
            ->havingRaw('(bills.total - COALESCE(SUM(payments_made.amount),0)) > 0')
            ->get();

        return response()->json($data);
    }
}
