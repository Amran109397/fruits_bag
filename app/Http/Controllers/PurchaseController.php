<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PostingService;

class PurchaseController extends Controller
{
    public function index()
    {
        return DB::table('bills')->latest('id')->paginate(25);
    }

    public function store(Request $r)
    {
        // Auto-generate bill number (e.g. BILL-00001)
        $lastId = DB::table('bills')->max('id') ?? 0;
        $billNumber = 'BILL-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        // Calculate total from items
        $total = 0;
        foreach ($r->items ?? [] as $it) {
            $lineTotal = ($it['qty'] ?? 0) * ($it['unit_price'] ?? 0);
            $total += $lineTotal;
        }

        // Insert bill
        $billId = DB::table('bills')->insertGetId([
            'vendor_id'   => $r->vendor_id,
            'bill_number' => $billNumber,
            'date'        => $r->date,
            'due_date'    => $r->due_date,
            'total'       => $total,
            'status'      => 'draft',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Insert items
        foreach ($r->items ?? [] as $it) {
            DB::table('bill_items')->insert([
                'bill_id'             => $billId,
                'description'         => $it['description'] ?? null,
                'qty'                 => $it['qty'] ?? 0,
                'unit_price'          => $it['unit_price'] ?? 0,
                'tax_amount'          => $it['tax_amount'] ?? 0,
                'line_total_excl_tax' => ($it['qty'] ?? 0) * ($it['unit_price'] ?? 0),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return response()->json([
            'id'     => $billId,
            'number' => $billNumber,
            'status' => 'draft',
            'total'  => $total,
        ]);
    }

    public function post(int $id, PostingService $posting)
    {
        $posting->postPurchaseBill($id);
        return response()->json(['status' => 'posted']);
    }

    public function pay(Request $r)
    {
        DB::table('payments_made')->insert([
            'vendor_id'  => $r->vendor_id,
            'bill_id'    => $r->bill_id,
            'amount'     => $r->amount,
            'status'     => 'posted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
