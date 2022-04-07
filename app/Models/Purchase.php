<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    protected $fillable = [
        'serial_no',
        'vendor_id',
        'date_time',
        'description',
        'user_id',
        'currency_id',
        'service_cost',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function vendor_id()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function items()
    {
        return $this->hasMany(StockRecord::class, 'type_id');
    }
    public function stock()
    {
        return $this->hasMany(StockRecord::class, 'type_id');
    }
}
