<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class ProData extends Model
{
  protected $fillable = [
    'proposal_id',
    'project_id',
    'company_id',
    'client_id',
    'title',
    'reference_no',
    'pr_worth',
    'deposit',
    'tax',
    'transit',
    'others',
    'discount',
    'currency_id',
    'total_price'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class, 'project_id');
  }
  public function proposal()
  {
    return $this->belongsTo(Proposal::class, 'proposal_id');
  }
  public function client()
  {
    return $this->belongsTo(Client::class, 'client_id');
  }
  public function currency()
  {
    return $this->belongsTo(Currency::class, 'currency_id');
  }
  public function company_id()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }
  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }
}
