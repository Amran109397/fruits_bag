<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PostingService
{
    /**
     * Sales Invoice পোস্ট করলে জার্নাল এন্ট্রি তৈরি করবে
     */
    public function postSalesInvoice(int $invoiceId): void
    {
        DB::transaction(function () use ($invoiceId) {
            $invoice = DB::table('invoices')->lockForUpdate()->find($invoiceId);
            if (!$invoice || $invoice->status === 'posted') return;

            $items = DB::table('invoice_items')->where('invoice_id', $invoiceId)->get();

            // Chart of Accounts 
            $ar     = $this->accountId('Accounts Receivable');
            $rev    = $this->accountId('Sales Revenue');
            $vatOut = $this->accountId('VAT Payable');

            // Journal Entry 
            $jeId = DB::table('journal_entries')->insertGetId([
                'date' => $invoice->date,
                'memo' => 'Sales Invoice #' . $invoice->invoice_number,
                'posted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // AR (Dr)
            DB::table('journal_entry_items')->insert([
                'journal_entry_id' => $jeId,
                'account_id' => $ar,
                'debit' => $invoice->total,
                'credit' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Revenue (Cr)
            $revenue = $items->sum('line_total_excl_tax');
            if ($revenue > 0) {
                DB::table('journal_entry_items')->insert([
                    'journal_entry_id' => $jeId,
                    'account_id' => $rev,
                    'debit' => 0,
                    'credit' => $revenue,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // VAT (Cr)
            $vat = $items->sum('tax_amount');
            if ($vat > 0) {
                DB::table('journal_entry_items')->insert([
                    'journal_entry_id' => $jeId,
                    'account_id' => $vatOut,
                    'debit' => 0,
                    'credit' => $vat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('invoices')->where('id', $invoiceId)->update([
                'status' => 'posted',
                'posted_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Purchase Bill পোস্ট করলে জার্নাল তৈরি করবে
     */
    public function postPurchaseBill(int $billId): void
    {
        DB::transaction(function () use ($billId) {
            $bill = DB::table('bills')->lockForUpdate()->find($billId);
            if (!$bill || $bill->status === 'posted') return;

            $items = DB::table('bill_items')->where('bill_id', $billId)->get();

            $ap    = $this->accountId('Accounts Payable');
            $exp   = $this->accountId('Purchases/COGS');
            $vatIn = $this->accountId('VAT Receivable');

            $jeId = DB::table('journal_entries')->insertGetId([
                'date' => $bill->date,
                'memo' => 'Purchase Bill #' . $bill->bill_number,
                'posted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Expense (Dr)
            $base = $items->sum('line_total_excl_tax');
            if ($base > 0) {
                DB::table('journal_entry_items')->insert([
                    'journal_entry_id' => $jeId,
                    'account_id' => $exp,
                    'debit' => $base,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // VAT (Dr)
            $vat = $items->sum('tax_amount');
            if ($vat > 0) {
                DB::table('journal_entry_items')->insert([
                    'journal_entry_id' => $jeId,
                    'account_id' => $vatIn,
                    'debit' => $vat,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // A/P (Cr)
            DB::table('journal_entry_items')->insert([
                'journal_entry_id' => $jeId,
                'account_id' => $ap,
                'debit' => 0,
                'credit' => $bill->total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('bills')->where('id', $billId)->update([
                'status' => 'posted',
                'posted_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    private function accountId(string $name): int
    {
        return (int) DB::table('chart_of_accounts')->where('name', $name)->value('id');
    }
}
