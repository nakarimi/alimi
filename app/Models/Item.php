<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'type_id',
        'name',
        'uom_id',
        'uom_equiv_id',
        'equivalent',
        'description',
    ];
    public function stock_records()
    {
        return $this->hasMany(StockRecord::class, 'item_id');
    }
    public function type()
    {
        return $this->belongsTo(ItemType::class, 'type_id');
    }
    public function measurment_unites_min()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'uom_id');
    }
    public function measurment_unites_sub()
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

}
