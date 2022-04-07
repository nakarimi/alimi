<?php

namespace App\Models;

use App\Models\Account;
use App\Models\ExchangeRate;
use App\Models\Proposal;
use App\Models\UserAssignment;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;




class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'user_type','position', 'email',
        'phone', 'address', 'password', 'image'


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'user_id');
    }
    public function user_assignments()
    {
        return $this->hasMany(UserAssignment::class, 'user_id');
    }
    public function exchange_rates()
    {
        return $this->hasMany(ExchangeRate::class, 'user_id');
    }
    public function user_notifications()
    {
        return $this->hasMany(UserNotification::class, 'user_id');
    }
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'user_id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id');
    }
    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'user_id');
    }
}
