<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentReceived;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    /**
     * Show all invoices
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'items'])
            ->orderByDesc('id')
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'date' => $inv->date,
                    'customer_name' => optional($inv->customer)->name,
                    'total' => $inv->total,
                    'paid_amount' => $inv->paid_amount ?? 0,
                    'posted_at' => $inv->posted_at,
                    'items' => $inv->items,
                ];
            });

        return response()->json($invoices);
    }

    /**
     * Store new invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $nextId = Invoice::max('id') + 1;
            $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            $total = collect($request->items)->sum(fn($i) => $i['qty'] * $i['rate']);

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'total' => $total,
                'paid_amount' => 0,
                'posted_at' => null
            ]);

            foreach ($request->items as $item) {
                $lineTotal = $item['qty'] * $item['rate'];
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['rate'],
                    'line_total_excl_tax' => $lineTotal,
                    'total' => $lineTotal,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully ✅',
                'invoice' => $invoice->load('items')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Invoice save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Post invoice
     */
    public function post($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['success' => false, 'error' => 'Invoice not found'], 404);
        }

        if ($invoice->posted_at) {
            return response()->json(['success' => true, 'message' => 'Invoice already posted']);
        }

        $invoice->update(['posted_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Invoice posted successfully ✅']);
    }

    /**
     * Unpost invoice
     */
    public function unpost($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['success' => false, 'error' => 'Invoice not found'], 404);
        }

        $invoice->update(['posted_at' => null]);

        return response()->json(['success' => true, 'message' => 'Invoice unposted successfully ⚙️']);
    }

    /**
     * Receive Payment + log in payment_receiveds table
     */
    public function receivePayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::find($request->invoice_id);

            // ✅ Update invoice total paid amount
            $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $request->amount;
            $invoice->save();

            // ✅ Log payment in PaymentReceived table
            PaymentReceived::create([
    'invoice_id' => $invoice->id,
    'customer_id' => $invoice->customer_id,
    'amount' => $request->amount,
    'payment_date' => $request->date, // ← যদি তোমার কলাম এই নামে থাকে
]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment received successfully ✅'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment receive error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
