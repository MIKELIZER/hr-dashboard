<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::all();
        return view('hr.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('hr.schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|integer'
        ]);
        WorkSchedule::create($request->all());
        return redirect()->route('hr.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(WorkSchedule $schedule)
    {
        return view('hr.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, WorkSchedule $schedule)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|integer'
        ]);
        $schedule->update($request->all());
        return redirect()->route('hr.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(WorkSchedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('hr.schedules.index')->with('success', 'Schedule deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('hr.schedules.index')->with('error', 'Cannot delete schedule because it is assigned to employees.');
        }
    }
}
