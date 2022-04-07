<?php

namespace App\Models;

use App\Models\MeasurmentUnit;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'manager',
        'phone',
        'address',
        'capacity',
        'oum_id'
    ];

    public function measur_unit()
    {
        return $this->belongsTo(MeasurmentUnit::class, 'oum_id');
    }

}
