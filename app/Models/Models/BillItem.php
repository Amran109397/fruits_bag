<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id','description','qty','unit_price','tax_amount','line_total_excl_tax'
    ];

    public function bill() { return $this->belongsTo(Bill::class); }
}
