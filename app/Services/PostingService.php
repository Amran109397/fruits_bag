<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PostingService
{
    public static function postJournalEntry($entryId)
    {
        $entry = DB::table('journal_entries')->find($entryId);
        if (!$entry) {
            throw new \Exception('Journal Entry not found.');
        }

        $items = DB::table('journal_entry_items')->where('journal_entry_id', $entryId)->get();
        if ($items->isEmpty()) {
            throw new \Exception('No items found for posting.');
        }

        $totalDebit = $items->sum('debit');
        $totalCredit = $items->sum('credit');

        if ($totalDebit != $totalCredit) {
            throw new \Exception('Journal is not balanced.');
        }

        DB::table('journal_entries')->where('id', $entryId)->update(['posted_at' => now()]);
    }
}
