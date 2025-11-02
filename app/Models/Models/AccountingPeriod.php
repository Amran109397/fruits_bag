<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['start_date','end_date','is_closed'];
    protected $casts = ['start_date'=>'date','end_date'=>'date','is_closed'=>'boolean'];
}
