<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    
    protected $fillable = [
        'serial_no', 'currency_id', 'datetime','title', 'transaction_status',
        'ammount', 'description', 'user_id', 'credit_desc', 'debit_desc', 'step'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function frs()
    {
        return $this->hasMany(FinancialRecord::class, 'id');
    }
}
