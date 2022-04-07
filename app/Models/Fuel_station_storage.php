<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel_station_storage extends Model
{
    //
    protected $fillable = [
        'station_id',
        'name',
        'supervisor',
        'capacity',
        'oum_id'
    ];

    public function fuel_station()
    {
        return $this->belongsTo(Fuel_station::class, 'station_id');
    }
    public function measur_unit()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'oum_id');
    }
}
