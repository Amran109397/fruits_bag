<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMade extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','bill_id','amount','status'];

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function bill() { return $this->belongsTo(Bill::class); }
}
