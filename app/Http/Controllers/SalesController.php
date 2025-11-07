<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PostingService;

class SalesController extends Controller
{
    public function index()
    {
        return DB::table('invoices')->latest('id')->paginate(25);
    }

    public function store(Request $r)
    {
        // Auto-generate invoice number (e.g. INV-00001)
        $lastId = DB::table('invoices')->max('id') ?? 0;
        $invoiceNumber = 'INV-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        // মোট amount হিসাব করা (items থেকে)
        $total = 0;
        foreach ($r->items ?? [] as $it) {
            $lineTotal = ($it['qty'] ?? 0) * ($it['unit_price'] ?? 0);
            $total += $lineTotal;
        }

        // invoice insert
        $invoiceId = DB::table('invoices')->insertGetId([
            'customer_id'    => $r->customer_id,
            'invoice_number' => $invoiceNumber,
            'date'           => $r->date,
            'due_date'       => $r->due_date,
            'total'          => $total,
            'status'         => 'draft',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // items insert
        foreach ($r->items ?? [] as $it) {
            DB::table('invoice_items')->insert([
                'invoice_id'           => $invoiceId,
                'description'          => $it['description'] ?? null,
                'qty'                  => $it['qty'] ?? 0,
                'unit_price'           => $it['unit_price'] ?? 0,
                'tax_amount'           => $it['tax_amount'] ?? 0,
                'line_total_excl_tax'  => ($it['qty'] ?? 0) * ($it['unit_price'] ?? 0),
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        return response()->json([
            'id'     => $invoiceId,
            'number' => $invoiceNumber,
            'status' => 'draft',
            'total'  => $total,
        ]);
    }

    public function post(int $id, PostingService $posting)
    {
        $posting->postSalesInvoice($id);
        return response()->json(['status' => 'posted']);
    }

    public function receive(Request $r)
    {
        // payments_received (ডেমো)
        DB::table('payments_received')->insert([
            'customer_id' => $r->customer_id,
            'invoice_id'  => $r->invoice_id,
            'amount'      => $r->amount,
            'status'      => 'posted',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // চাইলে এখানে Bank/AR journal তৈরি করতে পারো
        return response()->json(['status' => 'ok']);
    }
}
