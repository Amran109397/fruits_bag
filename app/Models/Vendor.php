<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','phone','address'];

    public function bills() { return $this->hasMany(Bill::class); }
    public function paymentsMade() { return $this->hasMany(PaymentMade::class); }
}
