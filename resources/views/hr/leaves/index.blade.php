@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Leave Requests Approval</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $req)
                    <tr>
                        <td>{{ $req->employee->user->name ?? 'Unknown' }}</td>
                        <td>{{ Carbon\Carbon::parse($req->start_date)->format('Y-m-d') }}</td>
                        <td>{{ Carbon\Carbon::parse($req->end_date)->format('Y-m-d') }}</td>
                        <td>{{ $req->reason }}</td>
                        <td>
                            <span class="badge bg-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>
                            @if($req->status == 'pending')
                                <form action="{{ route('hr.leave-requests.approve', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this leave request? This will deduct their balance.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form action="{{ route('hr.leave-requests.reject', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this leave request?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            @else
                                <span class="text-muted small">Processed by {{ $req->approver->name ?? 'Unknown' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
