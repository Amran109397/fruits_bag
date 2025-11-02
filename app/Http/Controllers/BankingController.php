<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankingController extends Controller
{
    public function accounts() {
        return DB::table('bank_accounts')->get(); // টেবিল থাকলে
    }

    public function import(Request $r) { /* TODO: CSV -> bank_transactions */ }
    public function reconcile(Request $r) { /* TODO */ }
}
