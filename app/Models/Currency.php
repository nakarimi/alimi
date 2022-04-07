<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sign_en',
        'sign_fa',
    ];
    /**
     * Get the rate that owns the Currency.
     */
    public function rates()
    {
        return $this->hasMany(ExchangeRate::class, 'currency_id');
    }
    /**
     * Get the last rate that owns the Currency.
     */
    public function last_rate()
    {
        return $this->hasOne(ExchangeRate::class, 'currency_id')->latest();
    }

    // added by Ahamdi
    public function financial_records()
    {
        return $this->hasMany(FinancialRecord::class, 'currency_id');
    }
    public function pro_data()
    {
        return $this->hasMany(ProData::class, 'currency_id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'currency_id');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'currency_id');
    }
}
