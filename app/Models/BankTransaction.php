<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'transaction_date',
        'description',
        'amount',
        'type',
        'is_reconciled',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
