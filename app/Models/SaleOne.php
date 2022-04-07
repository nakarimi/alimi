<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOne extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_ones';

    protected $fillable = [
        'sales_id',
        'serial_no',
        'project_id',
        'destination',
        'transport_cost',
        'service_cost',
        'tax',
        'deposit',
        'total',
        'steps',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

}
