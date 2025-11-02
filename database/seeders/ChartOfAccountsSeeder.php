<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $now = now();

        $rows = [
            // Assets
            ['name' => 'Cash',                  'code' => '1000', 'type' => 'asset',     'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Bank',                  'code' => '1010', 'type' => 'asset',     'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Accounts Receivable',   'code' => '1100', 'type' => 'asset',     'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Inventory',             'code' => '1400', 'type' => 'asset',     'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'VAT Receivable',        'code' => '1500', 'type' => 'asset',     'created_at'=>$now,'updated_at'=>$now],

            // Liabilities
            ['name' => 'Accounts Payable',      'code' => '2000', 'type' => 'liability', 'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'VAT Payable',           'code' => '2100', 'type' => 'liability', 'created_at'=>$now,'updated_at'=>$now],

            // Equity
            ['name' => 'Owner Equity',          'code' => '3000', 'type' => 'equity',    'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Retained Earnings',     'code' => '3100', 'type' => 'equity',    'created_at'=>$now,'updated_at'=>$now],

            // Revenue
            ['name' => 'Sales Revenue',         'code' => '4000', 'type' => 'revenue',   'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Other Income',          'code' => '4900', 'type' => 'revenue',   'created_at'=>$now,'updated_at'=>$now],

            // Expenses
            ['name' => 'Purchases/COGS',        'code' => '5000', 'type' => 'expense',   'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Operating Expenses',    'code' => '6000', 'type' => 'expense',   'created_at'=>$now,'updated_at'=>$now],
            ['name' => 'Bank Charges',          'code' => '6100', 'type' => 'expense',   'created_at'=>$now,'updated_at'=>$now],
        ];

        DB::table('chart_of_accounts')->insert($rows);
    }
}
