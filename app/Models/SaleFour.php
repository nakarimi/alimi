<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleFour extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_fours';

    protected $fillable = [
        'sales_id',
        'serial_no',
        'client_name',
        'service_cost',
        'additional_cost',
        'total',
        'steps',
        'tax',
        'description',
        'destination',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

}
