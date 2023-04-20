<?php

namespace App\Http\Controllers;

use App\Models\PrimarySkill;
use App\Models\Resource_plan;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $skills = [];
    public function index(Request $request)
    {

        $request->validate([
            'manage_by'      => 'required|string',
            'reporting_to'   => 'required|string',
            'year'           => 'required',
            'month'          => 'required'
        ]);
        $query = PrimarySkill::query();

        if ($request->manage_by && $request->reporting_to) {
            $query->whereHas('users', function ($query) use ($request) {
                $query->where('manage_by', $request->manage_by)
                    ->where('reporting_to', $request->reporting_to);
            });
        }
        $query->with('users.resources', function ($query) use ($request) {
            $query->where('year', $request->year)
                ->where('month', $request->month);
            $fullTime = 0;
            $partTime = 0;
            $available = 0;
            foreach ($query->get() as $user) {
                if ($user->planned_hours > 120) {
                    $fullTime =  $fullTime + 1;
                } elseif ($user->planned_hours > 40 && $user->planned_hours < 119) {
                    $partTime = $partTime + 1;
                } elseif ($user->planned_hours > 0 && $user->planned_hours < 39) {
                    $available =  $available + 1;
                }
            }
            $totalUser = $fullTime + $partTime + $available;
            $this->skills = ['fullTime' => $fullTime, 'partTime' => $partTime, 'available' => $available, 'total_user' => $totalUser];
        });
        $data = $query->get();
        $count = $query->count();
        return response()->json([
            'count_skill' => $count,
            'data' => $data,
            'user_count' => $this->skills,
        ]);
    }
}
