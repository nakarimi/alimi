<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalStep extends Model
{
    protected $table = 'proposals_step';
    public $timestamps = false;
    protected $fillable = [
        'step',
        'proposal_id',
        'res_person',
        'is_recieved_cont',
        'is_participated',
        'prop_recieved_or_allow',
        'winner'
    ];
}
