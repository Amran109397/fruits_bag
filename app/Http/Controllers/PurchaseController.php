<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PostingService;

class PurchaseController extends Controller
{
    public function index() { return DB::table('bills')->latest('id')->paginate(25); }

    public function store(Request $r) {
        $billId = DB::table('bills')->insertGetId([
            'vendor_id' => $r->vendor_id,
            'bill_number' => $r->bill_number,
            'date' => $r->date,
            'due_date' => $r->due_date,
            'total' => $r->total,
            'status' => 'draft',
            'created_at'=>now(),'updated_at'=>now()
        ]);
        foreach ($r->items ?? [] as $it) {
            DB::table('bill_items')->insert([
                'bill_id'=>$billId,
                'description'=>$it['description'],
                'qty'=>$it['qty'],
                'unit_price'=>$it['unit_price'],
                'tax_amount'=>$it['tax_amount'] ?? 0,
                'line_total_excl_tax'=>$it['line_total_excl_tax'],
                'created_at'=>now(),'updated_at'=>now()
            ]);
        }
        return response()->json(['id'=>$billId,'status'=>'draft']);
    }

    public function post(int $id, PostingService $posting) {
        $posting->postPurchaseBill($id);
        return response()->json(['status'=>'posted']);
    }

    public function pay(Request $r) {
        DB::table('payments_made')->insert([
            'vendor_id'=>$r->vendor_id,
            'bill_id'=>$r->bill_id,
            'amount'=>$r->amount,
            'status'=>'posted',
            'created_at'=>now(),'updated_at'=>now()
        ]);
        return response()->json(['status'=>'ok']);
    }
}
