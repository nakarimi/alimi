<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleThree extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_threes';

    protected $fillable = [
        'sales_id',
        'serial_no',
        'project_id',
        'driver_name',
        'plate_no',
        'driver_phone',
        'service_cost',
        'tax',
        'deposit',
        'total',
        'steps',
        'description',
        'destination'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

}
