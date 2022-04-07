<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRecord extends Model
{
    protected $fillable = [
        'type',
        'type_id',
        'source',
        'source_id',
        'item_id',
        'increment',
        'decrement',
        'uom_id',
        'increment_equiv',
        'decrement_equiv',
        'uom_equiv_id',
        'density',
        'operation_id',
        'unit_price',
        'total_price',
        'remark',
        'ex_rate_id',
    ];
    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function exchange_rate()
    {
        return $this->belongsTo(ExchangeRate::class, 'ex_rate_id');
    }
    public function item_id()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function operation_id()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
    public function measur_unit()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'uom_equiv_id');
    }
    public function uom_id()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'uom_id');
    }
    public function uom_equiv_id()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'uom_equiv_id');
    }
    public function source_id()
    {
        return $this->belongsTo(Storage::class, 'source_id');
    }
}
