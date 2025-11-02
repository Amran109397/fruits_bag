<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';
    protected $fillable = ['name','code','type'];

    public function items() { return $this->hasMany(JournalEntryItem::class, 'account_id'); }
}
