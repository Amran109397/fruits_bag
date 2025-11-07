<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    // Get all bank accounts
    public function accountsIndex()
    {
        return DB::table('bank_accounts')->latest('id')->paginate(25);
    }

    // Create new bank account
    public function accountsStore(Request $r)
    {
        $data = $r->all();

        // Allow both 'name' or 'account_name'
        if (!isset($data['name']) && isset($data['account_name'])) {
            $data['name'] = $data['account_name'];
        }

        // Allow both 'current_balance' or 'balance'
        if (!isset($data['current_balance']) && isset($data['balance'])) {
            $data['current_balance'] = $data['balance'];
        }

        // Validation
        $validated = validator($data, [
            'name'            => 'required|string',
            'account_number'  => 'required|string',
            'bank_name'       => 'required|string',
            'current_balance' => 'required|numeric',
        ])->validate();

        // Insert into database (fixed: use 'name' instead of 'account_name')
        $id = DB::table('bank_accounts')->insertGetId([
            'name'            => $validated['name'],
            'account_number'  => $validated['account_number'],
            'bank_name'       => $validated['bank_name'],
            'current_balance' => $validated['current_balance'],
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'id'      => $id,
            'status'  => 'created',
            'message' => 'Bank account added successfully.'
        ], 201);
    }

    // Update existing bank account
    public function accountsUpdate(Request $r, int $id)
    {
        $validated = $r->validate([
            'name'            => 'sometimes|required|string',
            'account_number'  => 'sometimes|required|string',
            'bank_name'       => 'sometimes|required|string',
            'current_balance' => 'sometimes|required|numeric',
        ]);

        DB::table('bank_accounts')->where('id', $id)->update(array_merge(
            $validated,
            ['updated_at' => now()]
        ));

        return response()->json(['status' => 'updated']);
    }

    // Delete bank account
    public function accountsDelete(int $id)
    {
        DB::table('bank_accounts')->where('id', $id)->delete();
        return response()->json(['status' => 'deleted']);
    }

    // Get all bank transactions
    public function transactionsIndex()
    {
        return DB::table('bank_transactions')->latest('id')->paginate(25);
    }

    // Create new bank transaction
    public function transactionsStore(Request $r)
    {
        $validated = $r->validate([
            'bank_account_id' => 'required|integer|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal',
        ]);

        $id = DB::table('bank_transactions')->insertGetId([
            'bank_account_id' => $validated['bank_account_id'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'is_reconciled' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update bank balance automatically
        $this->updateBalance($validated['bank_account_id'], $validated['type'], $validated['amount']);

        return response()->json([
            'id' => $id,
            'status' => 'transaction_added'
        ], 201);
    }

    // Update existing transaction
    public function transactionsUpdate(Request $r, int $id)
    {
        $validated = $r->validate([
            'transaction_date' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'amount' => 'sometimes|required|numeric',
            'type' => 'sometimes|required|in:deposit,withdrawal',
        ]);

        DB::table('bank_transactions')->where('id', $id)->update(array_merge(
            $validated,
            ['updated_at' => now()]
        ));

        return response()->json(['status' => 'updated']);
    }

    // Delete transaction
    public function transactionsDelete(int $id)
    {
        DB::table('bank_transactions')->where('id', $id)->delete();
        return response()->json(['status' => 'deleted']);
    }

    // Helper function to update bank account balance
    private function updateBalance(int $bankAccountId, string $type, float $amount)
    {
        $account = DB::table('bank_accounts')->where('id', $bankAccountId)->first();
        if (!$account) return;

        $newBalance = $account->current_balance;
        if ($type === 'deposit') {
            $newBalance += $amount;
        } elseif ($type === 'withdrawal') {
            $newBalance -= $amount;
        }

        DB::table('bank_accounts')->where('id', $bankAccountId)->update([
            'current_balance' => $newBalance,
            'updated_at' => now(),
        ]);
    }
}
