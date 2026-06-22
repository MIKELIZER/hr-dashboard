<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $attendances = Attendance::with('employee.user', 'employee.department')
                        ->where('attendance_date', $date)
                        ->get();

        return view('hr.attendances.index', compact('attendances', 'date'));
    }
}
