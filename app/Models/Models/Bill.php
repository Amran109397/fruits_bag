<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id','bill_number','date','due_date','total','status','posted_at'
    ];
    protected $casts = ['date'=>'date','due_date'=>'date','posted_at'=>'datetime'];

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function items() { return $this->hasMany(BillItem::class); }
    public function payments() { return $this->hasMany(PaymentMade::class); }
}
