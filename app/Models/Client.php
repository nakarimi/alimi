<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'address',
        'logo',
        'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function pro_date()
    {
        return $this->hasMany(ProData::class, 'client_id');
    }
}
