<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'lead_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
