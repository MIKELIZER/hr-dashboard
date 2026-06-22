@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Attendances Monitoring</h1>
    <form action="{{ route('hr.attendances.index') }}" method="GET" class="d-flex">
        <input type="date" name="date" class="form-control me-2" value="{{ $date }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Records for {{ $date }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
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
                        <td>{{ $att->employee->user->name ?? 'Unknown' }}</td>
                        <td>{{ $att->employee->department->name ?? 'Unknown' }}</td>
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
                        <td colspan="7" class="text-center py-3">No attendance records found for this date.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
