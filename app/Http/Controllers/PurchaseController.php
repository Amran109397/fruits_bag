<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a list of bills (with vendor & payment info)
     */
    public function index(Request $r)
    {
        $query = DB::table('bills')
            ->leftJoin('vendors', 'bills.vendor_id', '=', 'vendors.id')
            ->select(
                'bills.*',
                'vendors.name as vendor_name',
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments_made WHERE payments_made.bill_id = bills.id) as paid_amount')
            )
            ->orderByDesc('bills.id');

        if ($r->vendor_id) {
            $query->where('bills.vendor_id', $r->vendor_id);
        }

        return $query->paginate(25);
    }

    public function show($id)
{
    $bill = DB::table('bills')
        ->leftJoin('vendors', 'bills.vendor_id', '=', 'vendors.id')
        ->select('bills.*', 'vendors.name as vendor_name')
        ->where('bills.id', $id)
        ->first();

    if (!$bill) {
        return response()->json(['error' => 'Bill not found'], 404);
    }

    $bill->items = DB::table('bill_items')
        ->where('bill_id', $id)
        ->select('id', 'description', 'qty', 'unit_price')
        ->get();

    return response()->json($bill);
}


    /**
     * Store new bill
     */
    public function store(Request $r)
    {
        $r->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $lastId = DB::table('bills')->max('id') ?? 0;
            $billNumber = 'BILL-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            $total = collect($r->items)->sum(fn($i) => $i['qty'] * $i['unit_price']);

            $billId = DB::table('bills')->insertGetId([
                'vendor_id' => $r->vendor_id,
                'bill_number' => $billNumber,
                'date' => $r->date,
                'total' => $total,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($r->items as $item) {
                DB::table('bill_items')->insert([
                    'bill_id' => $billId,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'line_total_excl_tax' => $item['qty'] * $item['unit_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Bill created successfully', 'id' => $billId], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    

    /**
     * Update existing bill
     */
    public function update(Request $r, $id)
    {
        $r->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = collect($r->items)->sum(fn($i) => $i['qty'] * $i['unit_price']);

            DB::table('bills')->where('id', $id)->update([
                'vendor_id' => $r->vendor_id,
                'date' => $r->date,
                'total' => $total,
                'updated_at' => now(),
            ]);

            DB::table('bill_items')->where('bill_id', $id)->delete();

            foreach ($r->items as $item) {
                DB::table('bill_items')->insert([
                    'bill_id' => $id,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'line_total_excl_tax' => $item['qty'] * $item['unit_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Bill updated successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Post a bill (mark as posted)
     */
    public function post($id)
    {
        DB::table('bills')->where('id', $id)->update([
            'status' => 'posted',
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'posted']);
    }

    /**
     * Unpost a bill (mark as draft again)
     */
    public function unpost($id)
    {
        DB::table('bills')->where('id', $id)->update([
            'status' => 'draft',
            'updated_at' => now(),
        ]);

        // Delete related payments when unposted
        DB::table('payments_made')->where('bill_id', $id)->delete();

        return response()->json(['status' => 'draft']);
    }

    /**
     * Make payment for a bill
     */
    public function pay(Request $r)
    {
        $r->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            DB::table('payments_made')->insert([
                'vendor_id' => $r->vendor_id,
                'bill_id' => $r->bill_id,
                'amount' => $r->amount,
                'status' => 'posted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalPaid = DB::table('payments_made')->where('bill_id', $r->bill_id)->sum('amount');
            $bill = DB::table('bills')->where('id', $r->bill_id)->first();

            $status = 'posted';
            if ($totalPaid >= $bill->total) {
                $status = 'paid';
            } elseif ($totalPaid > 0) {
                $status = 'partial';
            }

            DB::table('bills')->where('id', $r->bill_id)->update([
                'paid_amount' => $totalPaid,
                'status' => $status,
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['status' => $status]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Unpay (remove all payments for a bill)
     */
    public function unpay($id)
    {
        DB::beginTransaction();

        try {
            DB::table('payments_made')->where('bill_id', $id)->delete();

            DB::table('bills')->where('id', $id)->update([
                'paid_amount' => 0,
                'status' => 'posted',
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['status' => 'unpaid']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
