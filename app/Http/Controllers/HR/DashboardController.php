<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary Cards
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalPositions = Position::count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Attendance Today
        $today = Carbon::today()->format('Y-m-d');
        $attendanceToday = Attendance::where('attendance_date', $today)->count();
        $lateToday = Attendance::where('attendance_date', $today)->where('status', 'terlambat')->count();

        // Chart 1: Attendance trend (last 7 days)
        $attendanceTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $hadir = Attendance::where('attendance_date', $dateStr)->where('status', 'hadir')->count();
            $terlambat = Attendance::where('attendance_date', $dateStr)->where('status', 'terlambat')->count();
            $attendanceTrend[] = [
                'date' => $date->format('d M'),
                'hadir' => $hadir,
                'terlambat' => $terlambat,
            ];
        }

        // Chart 2: Employees per Department (Pie/Doughnut)
        $deptDistribution = Department::withCount('employees')->get()->map(function ($d) {
            return ['name' => $d->name, 'count' => $d->employees_count];
        });

        // Chart 3: Leave Requests Status Summary (Doughnut)
        $leaveStats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
        ];

        // Chart 4: Monthly Salary Expenditure (Bar chart - last 6 months)
        $salaryTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::today()->subMonths($i);
            $m = $monthDate->month;
            $y = $monthDate->year;
            $total = Salary::where('month', $m)->where('year', $y)->where('status', 'released')->sum('total_salary');
            $salaryTrend[] = [
                'period' => $monthDate->format('M Y'),
                'total' => (float) $total,
            ];
        }

        return view('hr.dashboard', compact(
            'totalEmployees', 'totalDepartments', 'totalPositions',
            'pendingLeaves', 'attendanceToday', 'lateToday',
            'attendanceTrend', 'deptDistribution', 'leaveStats', 'salaryTrend'
        ));
    }
}
