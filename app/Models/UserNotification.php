<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id',
        'notification_id',
        'status',
        'pin',
        'done',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
