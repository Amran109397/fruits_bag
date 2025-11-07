<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index()
    {
        return JournalEntry::with('items')->orderBy('date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'nullable|string',
            'items' => 'required|array|min:2'
        ]);

        $entry = JournalEntry::create([
            'date' => $request->date,
            'description' => $request->description
        ]);

        foreach ($request->items as $item) {
            JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $item['account_id'],
                'debit' => $item['debit'],
                'credit' => $item['credit'],
                'description' => $item['description'] ?? null,
            ]);
        }

        return response()->json($entry->load('items'), 201);
    }
}
