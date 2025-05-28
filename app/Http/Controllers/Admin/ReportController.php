<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'type' => 'required|in:financial,attendance,department_activity,goal,special_needs',
            'data' => 'required|json'
        ]);

        $report = Report::create([
            'uuid' => Str::uuid(),
            'fiscal_year_id' => $validated['fiscal_year_id'],
            'fiscal_period_id' => $request->input('fiscal_period_id'),
            'type' => $validated['type'],
            'data' => $validated['data']
        ]);

        return response()->json(['message' => 'Report created successfully!', 'report' => $report]);
    }

    public function index()
    {
        $reports = Report::latest()->paginate(10);
        return response()->json($reports);
    }

    public function show(Report $report)
    {
        return response()->json($report);
    }
}
