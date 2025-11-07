<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'bank_name',
        'current_balance',
    ];

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
