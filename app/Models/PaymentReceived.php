<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceived extends Model
{
    use HasFactory;

 
    protected $table = 'payments_received';

    protected $fillable = [
        'invoice_id',
        'customer_id',
        'amount',
        'date',
    ];
}
