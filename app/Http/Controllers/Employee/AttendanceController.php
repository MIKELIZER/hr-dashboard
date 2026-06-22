<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $attendances = Attendance::where('employee_id', $employee->id)
                        ->orderBy('attendance_date', 'desc')
                        ->get();
        return view('employee.attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $employee = Auth::user()->employee;
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');

        $exists = Attendance::where('employee_id', $employee->id)
                            ->where('attendance_date', $today)
                            ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'You have already checked in today.');
        }

        // Calculate late minutes based on schedule
        $schedule = $employee->schedule;
        $lateMinutes = 0;
        $status = 'hadir';

        if ($schedule) {
            $expectedStart = Carbon::parse($today . ' ' . $schedule->start_time);
            $actualStart = Carbon::parse($today . ' ' . $now);
            
            // Add tolerance
            $expectedStartWithTolerance = $expectedStart->copy()->addMinutes($schedule->late_tolerance);

            if ($actualStart->greaterThan($expectedStartWithTolerance)) {
                $lateMinutes = (int) abs($actualStart->diffInMinutes($expectedStart));
                $status = 'terlambat';
            }
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => $today,
            'check_in' => $now,
            'status' => $status,
            'late_minutes' => $lateMinutes,
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Checked in successfully.');
    }

    public function checkOut(Request $request)
    {
        $employee = Auth::user()->employee;
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');

        $attendance = Attendance::where('employee_id', $employee->id)
                            ->where('attendance_date', $today)
                            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'You have not checked in today.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'You have already checked out today.');
        }

        // Calculate work duration
        $checkInTime = Carbon::parse($today . ' ' . $attendance->check_in);
        $checkOutTime = Carbon::parse($today . ' ' . $now);
        $workDurationMinutes = (int) abs($checkInTime->diffInMinutes($checkOutTime));

        $attendance->update([
            'check_out' => $now,
            'work_duration_minutes' => $workDurationMinutes
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Checked out successfully.');
    }
}
