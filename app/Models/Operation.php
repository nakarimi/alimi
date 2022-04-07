<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'title',
        'formula',
        'description'
    ];

    public function stock_records()
    {
        return $this->hasMany(StockRecord::class, 'operation_id');
    }
    public function pro_items()
    {
        return $this->hasMany(ProItem::class, 'operation_id');
    }
}
