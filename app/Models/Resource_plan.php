<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource_plan extends Model
{
    use HasFactory, Uuids;
    protected $table = 'resource_plans';

    public function ResourcePlan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
