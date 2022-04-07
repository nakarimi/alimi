<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archives_file extends Model
{
    protected $fillable = [
        'archive_id',
        'origname',
        'path',
        'newname',
        'mime',
        'caption'
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class, 'archive_id');
    }
}
