<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel_station extends Model
{
    //
    protected $fillable = [
        'name',
        'address',
        'manager',
        'phone'
    ];

    public function fuel_despencers()
    {
        return $this->hasMany(Fuel_despenser::class, 'station_id');
    }
    public function fuel_station_storages()
    {
        return $this->hasMany(Fuel_station_storage::class, 'station_id');
    }
}
