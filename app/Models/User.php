<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Traits\Uuids;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Has many realtionship with Resource Plan of user*/
    public function resources()
    {
        return $this->hasMany(ResourcePlan::class, 'user_id')->select('id', 'user_id', 'year', 'month', 'planned_hours');
    }
    /* belongsToMany realtionship with Primary Skill of user*/

    public function skills()
    {
        return $this->belongsToMany(PrimarySkill::class, 'primary_skill_users', 'user_id', 'primary_skill_id');
    }

    //get full name of users
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }
}
