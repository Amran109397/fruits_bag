<?php

namespace App\Http\Controllers;

use App\Models\AccountingPeriod;
use Illuminate\Http\Request;

class AccountingPeriodController extends Controller
{
    public function index()
    {
        return AccountingPeriod::with('fiscalYear')->get();
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        return AccountingPeriod::create($data);
    }

    public function update(Request $r, AccountingPeriod $accountingPeriod)
    {
        $accountingPeriod->update($r->only('name', 'start_date', 'end_date', 'is_closed'));
        return $accountingPeriod;
    }

    public function destroy(AccountingPeriod $accountingPeriod)
    {
        $accountingPeriod->delete();
        return response()->noContent();
    }
}
