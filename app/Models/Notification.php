<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $fillable = [
    'title',
    'text',
    'type',
    'gen_date',
    'exp_date',
    'action',
    'url',
    'user_id',
    'notif_number',
    'notif_source',
    'notif_source_id',
  ];

  public function user_notification()
  {
    return $this->belongsTo(UserNotification::class, 'id' , 'notification_id')->orderBy('id', 'asc');
  }
}
