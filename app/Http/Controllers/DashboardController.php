<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        $today = now()->toDateString();
        $m0    = now()->startOfMonth()->toDateString();

        $salesMTD = DB::table('invoices')->where('status','posted')
            ->whereBetween('posted_at',[$m0,$today])->sum('total');

        $arDue = DB::selectOne("
          SELECT COALESCE(SUM(i.total - COALESCE(p.paid,0)),0) AS due
          FROM invoices i
          LEFT JOIN (SELECT invoice_id, SUM(amount) paid FROM payments_received
                     WHERE status='posted' GROUP BY invoice_id) p
          ON p.invoice_id = i.id
          WHERE i.status='posted'
        ")->due;

        $apDue = DB::selectOne("
          SELECT COALESCE(SUM(b.total - COALESCE(p.paid,0)),0) AS due
          FROM bills b
          LEFT JOIN (SELECT bill_id, SUM(amount) paid FROM payments_made
                     WHERE status='posted' GROUP BY bill_id) p
          ON p.bill_id = b.id
          WHERE b.status='posted'
        ")->due;

        $inventory = DB::table('journal_entry_items as j')
            ->join('journal_entries as e','e.id','=','j.journal_entry_id')
            ->join('chart_of_accounts as c','c.id','=','j.account_id')
            ->where('c.type','asset')->where('c.name','like','%Inventory%')
            ->selectRaw('SUM(j.debit - j.credit) as bal')->value('bal');

        return response()->json([
            'sales_mtd' => (float)$salesMTD,
            'ar_due' => (float)$arDue,
            'ap_due' => (float)$apDue,
            'inventory_value' => (float)($inventory ?? 0),
        ]);
    }

    public function arAging() { /* TODO */ }
    public function apAging() { /* TODO */ }
    public function topCustomers() { /* TODO */ }
    public function bankRecoStatus() { /* TODO */ }
}
