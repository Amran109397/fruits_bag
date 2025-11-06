<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = ['date','memo','posted_at'];
    protected $casts = ['date'=>'date','posted_at'=>'datetime'];

    public function items() { return $this->hasMany(JournalEntryItem::class); }
}
