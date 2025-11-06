<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryItem extends Model
{
    use HasFactory;

    protected $fillable = ['journal_entry_id','account_id','debit','credit'];

    public function entry() { return $this->belongsTo(JournalEntry::class, 'journal_entry_id'); }
    public function account() { return $this->belongsTo(ChartOfAccount::class, 'account_id'); }
}
