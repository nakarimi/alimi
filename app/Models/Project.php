<?php

namespace App\Models;

use App\Models\ProData;
use App\Models\ProItem;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_no',
        'proposal_id',
        'contract_date',
        'contract_end_date',
        'project_guarantee',
        'status',
        'user_id',
    ];

    public function pro_data()
    {
        return $this->hasOne(ProData::class, 'project_id');
    }
    public function pro_items()
    {
        return $this->hasMany(ProItem::class, 'project_id');
    }
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    public function proposal_id()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
