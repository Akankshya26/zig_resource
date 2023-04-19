<?php

namespace App\Http\Controllers;

use App\Models\PrimarySkill;
use App\Models\Resource_plan;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            // 'manage_by'      => 'required|string',
            // 'reporting_to'   => 'required|string',
            // 'year'           => 'required',
            // 'month'          => 'required'
        ]);
        $query = User::with('Skills:name');

        // dd($query->planned_hours);
        if ($request->year && $request->month) {
            $query->whereHas('Resources', function ($query) use ($request) {
                $query->where('year', $request->year)
                    ->where('month', $request->month);
            });
        }

        if ($request->manage_by && $request->reporting_to) {
            $query->where('manage_by', $request->manage_by)
                ->where('reporting_to', $request->reporting_to);
        }
        // if ($query->Resources->planned_hours > 120) {
        //     return "ok";
        // }

        /* Pagination */
        $count = $query->count();
        if ($request->page && $request->perPage) {
            $page = $request->page;
            $perPage = $request->perPage;
            $query = $query->skip($perPage * ($page - 1))->take($perPage);
        }

        $data = $query->get();
        // dd($data);
        return response()->json([
            'count_user' => $count,
            'data' => $data

        ]);
    }
}
// }
