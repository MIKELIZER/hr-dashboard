<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('employee.user', 'approver')
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('hr.leaves.index', compact('leaveRequests'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request is already processed.');
        }

        $start = Carbon::parse($leaveRequest->start_date);
        $end = Carbon::parse($leaveRequest->end_date);
        $days = $start->diffInDays($end) + 1;

        $year = $start->format('Y');

        DB::transaction(function() use ($leaveRequest, $days, $year) {
            $balance = LeaveBalance::firstOrCreate(
                ['employee_id' => $leaveRequest->employee_id, 'year' => $year],
                ['total_quota' => 12, 'used_quota' => 0, 'remaining_quota' => 12]
            );

            if ($balance->remaining_quota < $days) {
                throw new \Exception('Insufficient leave balance.');
            }

            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $balance->update([
                'used_quota' => $balance->used_quota + $days,
                'remaining_quota' => $balance->remaining_quota - $days,
            ]);

            LeaveHistory::create([
                'leave_balance_id' => $balance->id,
                'leave_request_id' => $leaveRequest->id,
                'type' => 'deduct',
                'amount' => $days,
                'description' => 'Leave approved: ' . $leaveRequest->reason,
            ]);
        });

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request is already processed.');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
}
