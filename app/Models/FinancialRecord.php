<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    protected $fillable = [
        'type',
        'type_id',
        'account_id',
        'description',
        'currency_id',
        'credit',
        'debit',
        'ex_rate_id',
        'status'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function exchange_rate()
    {
        return $this->belongsTo(ExchangeRate::class, 'ex_rate_id');
    }
}
