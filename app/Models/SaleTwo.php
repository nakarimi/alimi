<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTwo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_twos';

    protected $fillable = [
        'sales_id',
        'serial_no',
        'client_id',
        'storage_id',
        'destination',
        'transport_cost',
        'service_cost',
        'tax',
        'total',
        'steps',
        'description',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function client_id()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

}
