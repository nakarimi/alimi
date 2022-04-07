<?php

namespace App\Models;

use App\Models\Archives_file;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'title',
        'refcode',
        'account_id',
        'note',
        'user_id',
        'type'
    ];

    public function files()
    {
        return $this->hasMany(Archives_file::class, 'archive_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
