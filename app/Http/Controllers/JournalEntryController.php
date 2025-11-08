<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JournalEntryController extends Controller
{
    /**
     * Show all journal entries
     */
    public function index()
    {
        $entries = DB::table('journal_entries')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($entry) {
                $entry->items = DB::table('journal_entry_items')
                    ->where('journal_entry_id', $entry->id)
                    ->get();
                return $entry;
            });

        return response()->json($entries);
    }

    /**
     * Store a new journal entry (even if not balanced)
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $entryId = DB::table('journal_entries')->insertGetId([
                'date' => $request->date,
                'memo' => $request->description ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($request->items as $item) {
                $debit = floatval($item['debit'] ?? 0);
                $credit = floatval($item['credit'] ?? 0);
                $totalDebit += $debit;
                $totalCredit += $credit;

                DB::table('journal_entry_items')->insert([
                    'journal_entry_id' => $entryId,
                    'account_id' => $item['account_id'],
                    'debit' => $debit,
                    'credit' => $credit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            $balanced = ($totalDebit == $totalCredit);

            return response()->json([
                'message' => $balanced
                    ? 'Journal entry created successfully.'
                    : 'Journal saved as draft (unbalanced).',
                'balanced' => $balanced,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Post a journal entry (only if balanced)
     */
    public function post($id)
    {
        $entry = DB::table('journal_entries')->find($id);
        if (!$entry) {
            return response()->json(['error' => 'Entry not found.'], 404);
        }

        $items = DB::table('journal_entry_items')->where('journal_entry_id', $id)->get();
        $totalDebit = $items->sum('debit');
        $totalCredit = $items->sum('credit');

        if ($totalDebit != $totalCredit) {
            return response()->json([
                'error' => 'Cannot post. Journal is not balanced.',
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit
            ], 422);
        }

        if ($entry->posted_at) {
            return response()->json(['message' => 'Already posted.']);
        }

        DB::table('journal_entries')
            ->where('id', $id)
            ->update(['posted_at' => Carbon::now()]);

        return response()->json(['message' => 'Journal Entry posted successfully.']);
    }

    /**
     * Unpost a journal entry
     */
    public function unpost($id)
    {
        $entry = DB::table('journal_entries')->find($id);
        if (!$entry) {
            return response()->json(['error' => 'Entry not found.'], 404);
        }

        DB::table('journal_entries')
            ->where('id', $id)
            ->update(['posted_at' => null]);

        return response()->json(['message' => 'Journal Entry unposted successfully.']);
    }
}
