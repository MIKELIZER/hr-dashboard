<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('employee.leaves.index', compact('leaveRequests'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;
        $balance = LeaveBalance::firstOrCreate(
            ['employee_id' => $employee->id, 'year' => date('Y')],
            ['total_quota' => 12, 'used_quota' => 0, 'remaining_quota' => 12]
        );

        return view('employee.leaves.create', compact('balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Auth::user()->employee;

        // Optionally, check if they have enough balance here, though it's technically checked on approval too.
        
        LeaveRequest::create([
            'employee_id' => $employee->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('employee.leave-requests.index')->with('success', 'Leave request submitted successfully.');
    }
}
