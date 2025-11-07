<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountsController extends Controller
{
    public function index()
    {
        return ChartOfAccount::orderBy('code')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:chart_of_accounts,code',
            'name' => 'required|string',
            'type' => 'required|string',
            'normal_balance' => 'required|string'
        ]);

        return ChartOfAccount::create($validated);
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        return $chartOfAccount;
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->update($request->only('name', 'type', 'normal_balance'));
        return $chartOfAccount;
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->delete();
        return response()->noContent();
    }
}
