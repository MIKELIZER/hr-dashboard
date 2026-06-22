@extends('layouts.employee')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Attendances</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Late (Mins)</th>
                        <th>Duration (Mins)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td>{{ $att->attendance_date->format('Y-m-d') }}</td>
                        <td>{{ $att->check_in ?? '-' }}</td>
                        <td>{{ $att->check_out ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $att->status == 'hadir' ? 'success' : ($att->status == 'terlambat' ? 'warning' : 'danger') }}">
                                {{ ucfirst($att->status) }}
                            </span>
                        </td>
                        <td>{{ $att->late_minutes }}</td>
                        <td>{{ $att->work_duration_minutes }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
