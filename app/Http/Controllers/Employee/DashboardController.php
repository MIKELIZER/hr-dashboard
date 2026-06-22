<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LeaveBalance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;
        $today = Carbon::today()->format('Y-m-d');
        
        $attendanceToday = Attendance::where('employee_id', $employee->id)
                                    ->where('attendance_date', $today)
                                    ->first();

        $leaveBalance = LeaveBalance::where('employee_id', $employee->id)
                                    ->where('year', date('Y'))
                                    ->first();

        // Period filter (default: 6 months)
        $period = $request->input('period', 6);
        $validPeriods = [3, 6, 12];
        if (!in_array((int)$period, $validPeriods)) $period = 6;

        // Monthly attendance summary
        $attendanceSummary = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $monthDate = Carbon::today()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();

            // If current month, cap at today
            if ($monthEnd->greaterThan(Carbon::today())) {
                $monthEnd = Carbon::today();
            }

            // Count working days (weekdays) in this month
            $workingDays = 0;
            $cursor = $monthStart->copy();
            while ($cursor->lessThanOrEqualTo($monthEnd)) {
                if (!$cursor->isWeekend()) $workingDays++;
                $cursor->addDay();
            }

            $hadir = Attendance::where('employee_id', $employee->id)
                        ->whereBetween('attendance_date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                        ->where('status', 'hadir')->count();
            $terlambat = Attendance::where('employee_id', $employee->id)
                        ->whereBetween('attendance_date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                        ->where('status', 'terlambat')->count();
            $totalAbsensi = $hadir + $terlambat;
            $tidakHadir = max($workingDays - $totalAbsensi, 0);
            
            $attendanceSummary[] = [
                'month' => $monthDate->format('M Y'),
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tidak_hadir' => $tidakHadir,
                'working_days' => $workingDays,
                'total' => $totalAbsensi,
            ];
        }

        return view('employee.dashboard', compact('employee', 'attendanceToday', 'leaveBalance', 'attendanceSummary', 'period'));
    }
}
