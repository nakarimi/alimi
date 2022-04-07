<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'currency_id',
        'user_id',
        'rate',
        'system_rate',
        'counter',
    ];

    /**
     * Get the currency that owns the phone.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    // added by Ahmadi.
    public function financial_records()
    {
        return $this->hasMany(FinancialRecord::class, 'ex_rate_id');
    }
}
