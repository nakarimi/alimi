<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participator extends Model
{
    protected $table = 'participators';
    public $timestamps = false;
    protected $fillable = [
        'proposal_id',
        'name',
        'same_dificult',
        'statement',
        'feyat',
        'offer_price',
        'discount',
        'final_price'
    ];
}
