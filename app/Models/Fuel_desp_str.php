<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel_desp_str extends Model
{
    //	
    protected $fillable = [
        'storage_id',
        'despencer_id'
    ];

    public function fuel_station()
    {
        return $this->belongsTo(Fuel_despenser::class, 'storage_id');
    }
    public function measur_unit()
    {
        return $this->belongsTo(Fuel_station_storage::class, 'despencer_id');
    }
}
