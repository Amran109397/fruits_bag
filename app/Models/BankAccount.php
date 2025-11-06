<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'bank_name',
    ];

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
