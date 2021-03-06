<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $table = 'companies';
    protected $fillable = [
        'label',
        'sign',
        'bossname',
        'logo',
        'TIN',
        'lisense'
    ];
}
