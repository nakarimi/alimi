<?php

namespace App\Models;

use App\Models\Fuel_station_storage;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Model;

class MeasurmentUnit extends Model
{
    protected $fillable = [
        'title',
        'acronym',
    ];

    public function pro_items()
    {
        return $this->hasMany(ProItem::class, 'unit_id');
    }
    public function items_uom()
    {
        return $this->hasMany(Itme::class, 'uom_id');
    }
    public function items_uom_equiv()
    {
        return $this->hasMany(Item::class, 'uom_equiv_id');
    }
    public function uom_id()
    {
        return $this->hasMany(Itme::class, 'uom_id');
    }
    public function uom_equiv_id()
    {
        return $this->hasMany(Item::class, 'uom_equiv_id');
    }
    public function stock_records()
    {
        return $this->hasMany(StockRecord::class, 'item_id');
    }
    public function storages()
    {
        return $this->hasMany(Storage::class, 'oum_id');
    }
    public function fuel_station_storages()
    {
        return $this->hasMany(Fuel_station_storage::class, 'oum_id');
    }
}
