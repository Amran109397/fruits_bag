<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id','invoice_number','date','due_date','total','status','posted_at'
    ];
    protected $casts = ['date'=>'date','due_date'=>'date','posted_at'=>'datetime'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(PaymentReceived::class); }
}
