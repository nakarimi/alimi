<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'serial_no',
        'publish_date',
        'publish_address',
        'submission_date',
        'bidding_date',
        'bidding_address',
        'offer_guarantee',
        'status',
        'offer_guarantee_type',
        'same_pro',
        'deal_value',
        'financial_power',
        'receive_office',
        'bank_distribute',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function project()
    {
        return $this->hasOne(ProData::class, 'proposal_id');
    }
    public function pro_items()
    {
        return $this->hasMany(ProItem::class, 'proposal_id');
    }
    public function pro_data()
    {
        return $this->hasOne(ProData::class, 'proposal_id');
    }
}
