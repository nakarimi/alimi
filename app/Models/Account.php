<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'type_id',
        'name',
        'ref_code',
        'status',
        'description',
        'system',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'account_id');
    }
    public function type()
    {
        return $this->belongsTo(AccountType::class, 'type_id');
    }
    public function financial_records()
    {
        return $this->hasMany(FinancialRecord::class, 'account_id');
    }
    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'account_id');
    }
}
