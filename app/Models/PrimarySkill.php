<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimarySkill extends Model
{
    use HasFactory, Uuids;
    protected $table = 'primary_skills';


    public function users()
    {
        return $this->belongsToMany(User::class, 'primary_skill_users',  'primary_skill_id', 'user_id')->select('id', 'email', 'manage_by', 'reporting_to');
    }
}
