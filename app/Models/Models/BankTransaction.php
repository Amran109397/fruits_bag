<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'date',
        'description',
        'amount',
        'reference',
        'reconciled_at',
    ];

    protected $casts = [
        'date' => 'date',
        'reconciled_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
