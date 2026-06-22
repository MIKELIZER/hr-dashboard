@extends('layouts.employee')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Leave Requests</h2>
    <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-primary">Apply Leave</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $req)
                    <tr>
                        <td>{{ $req->start_date->format('Y-m-d') }}</td>
                        <td>{{ $req->end_date->format('Y-m-d') }}</td>
                        <td>{{ $req->reason }}</td>
                        <td>
                            <span class="badge bg-{{ $req->status == 'approved' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
