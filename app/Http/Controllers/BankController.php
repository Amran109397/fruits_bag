<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class BankController extends Controller
{
    // === Bank Accounts ===
    public function accountsIndex()
    {
        return BankAccount::latest('id')->paginate(50);
    }

    public function accountsStore(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:100',
            'number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:150',
        ]);
        return BankAccount::create($data);
    }

    public function accountsUpdate(Request $r, $id)
    {
        $account = BankAccount::findOrFail($id);
        $account->update($r->only('name','number','bank_name'));
        return $account;
    }

    public function accountsDelete($id)
    {
        $acc = BankAccount::findOrFail($id);
        $acc->delete();
        return response()->noContent();
    }

    // === Transactions ===
    public function transactionsIndex(Request $r)
    {
        $query = BankTransaction::query();
        if ($r->has('bank_account_id')) {
            $query->where('bank_account_id', $r->bank_account_id);
        }
        return $query->latest('date')->paginate(50);
    }

    public function transactionsStore(Request $r)
    {
        $data = $r->validate([
            'bank_account_id' => 'required|integer',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'reference' => 'nullable|string',
        ]);
        return BankTransaction::create($data);
    }

    public function transactionsUpdate(Request $r, $id)
    {
        $tx = BankTransaction::findOrFail($id);
        $tx->update($r->only('description','amount','reference','reconciled_at'));
        return $tx;
    }

    public function transactionsDelete($id)
    {
        $tx = BankTransaction::findOrFail($id);
        $tx->delete();
        return response()->noContent();
    }
}
