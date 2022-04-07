<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'account_id'
    ];
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'vendor_id');
    }
}
