<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $fillable = [
        'type',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'type_id');
    }
}
