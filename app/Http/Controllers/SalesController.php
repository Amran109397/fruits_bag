<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PostingService;

class SalesController extends Controller
{
    public function index() { return DB::table('invoices')->latest('id')->paginate(25); }

    public function store(Request $r) {
        // draft invoice + items (সিম্পল ডেমো)
        $invoiceId = DB::table('invoices')->insertGetId([
            'customer_id' => $r->customer_id,
            'invoice_number' => $r->invoice_number,
            'date' => $r->date,
            'due_date' => $r->due_date,
            'total' => $r->total,
            'status' => 'draft',
            'created_at'=>now(),'updated_at'=>now()
        ]);
        foreach ($r->items ?? [] as $it) {
            DB::table('invoice_items')->insert([
                'invoice_id'=>$invoiceId,
                'description'=>$it['description'],
                'qty'=>$it['qty'],
                'unit_price'=>$it['unit_price'],
                'tax_amount'=>$it['tax_amount'] ?? 0,
                'line_total_excl_tax'=>$it['line_total_excl_tax'],
                'created_at'=>now(),'updated_at'=>now()
            ]);
        }
        return response()->json(['id'=>$invoiceId,'status'=>'draft']);
    }

    public function post(int $id, PostingService $posting) {
        $posting->postSalesInvoice($id);
        return response()->json(['status'=>'posted']);
    }

    public function receive(Request $r) {
        // payments_received (ডেমো)
        DB::table('payments_received')->insert([
            'customer_id'=>$r->customer_id,
            'invoice_id'=>$r->invoice_id,
            'amount'=>$r->amount,
            'status'=>'posted',
            'created_at'=>now(),'updated_at'=>now()
        ]);
        // চাইলে এখানে Bank/AR জার্নালও করতে পারেন
        return response()->json(['status'=>'ok']);
    }
}
