<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResourcePlan extends Model
{
    use HasFactory, Uuids;
    protected $table = 'resource_plans';


    /* belongs To realtionship with User Plan of Resource plan*/

    public function ResourcePlan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
