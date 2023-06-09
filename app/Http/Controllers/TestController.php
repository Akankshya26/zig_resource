<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PrimarySkill;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {

        $request->validate([
            'manage_by'      => 'required|string|exists:users,manage_by',
            'reporting_to'   => 'required|string|exists:users,reporting_to',
            'year'           => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'month'          => 'required|between:1,12'
        ]);
        $primarySkills = PrimarySkill::query();

        if ($request->manage_by && $request->reporting_to) {
            $skill = $primarySkills->whereHas('users', function ($query) use ($request) {
                $query->where('manage_by', $request->manage_by)
                    ->where('reporting_to', $request->reporting_to);
            });
        }
        $totalFullTime  = 0;
        $totalPartTime  = 0;
        $totalAvailable = 0;
        $skills = $skill->get();
        foreach ($skills as $key => $skill) {
            $fullTime  = 0;
            $partTime  = 0;
            $available = 0;
            $users = $skill->users()->get();
            foreach ($users as $user) {
                $resouce = $user->resources()
                    ->where('year', $request->year)
                    ->where('month', $request->month)->sum('planned_hours');
                if ($resouce > 120) {
                    $fullTime =  $fullTime + 1;
                    $totalFullTime = $totalFullTime + 1;
                } elseif ($resouce > 40 && $resouce < 119) {
                    $partTime = $partTime + 1;
                    $totalPartTime = $totalPartTime + 1;
                } elseif ($resouce > 0 && $resouce < 39) {
                    $totalAvailable = $totalAvailable + 1;
                    $available =  $available + 1;
                }
                //Count Total user with respect to skill
                $totalSkillUser = $fullTime + $partTime + $available;
                $total_user = $totalFullTime + $totalPartTime + $totalAvailable;
                $skills[$key]['user_skills'] = [
                    'fullTime'   => $fullTime,
                    'partTime'   => $partTime,
                    'available'  => $available,
                    'total_user' =>  $totalSkillUser
                ];
            }
        }
        //Count Skills
        $count = $primarySkills->count();
        return response()->json([
            'count_skill'                  => $count,
            'user_count'                   => $skills,
            'totalFullTime'                => $totalFullTime,
            'totalPartTime'                => $totalPartTime,
            'totalAvailable'               => $totalAvailable,
            'Total_user'                   => $total_user,
        ]);
    }
}
