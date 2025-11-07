<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['fiscal_year_id', 'name', 'start_date', 'end_date', 'is_closed'];

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }
}
