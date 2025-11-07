<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index()
    {
        return FiscalYear::all();
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|unique:fiscal_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        return FiscalYear::create($data);
    }

    public function update(Request $r, FiscalYear $fiscalYear)
    {
        $fiscalYear->update($r->only('name', 'start_date', 'end_date', 'is_locked'));
        return $fiscalYear;
    }

    public function destroy(FiscalYear $fiscalYear)
    {
        $fiscalYear->delete();
        return response()->noContent();
    }
}
