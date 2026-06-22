@extends('layouts.employee')

@section('content')
<div class="mb-4">
    <h2>Apply for Leave</h2>
    <a href="{{ route('employee.leave-requests.index') }}" class="btn btn-secondary btn-sm">Back to History</a>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Your Remaining Quota:</strong> {{ $balance->remaining_quota }} days
        </div>
        
        <form action="{{ route('employee.leave-requests.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                @error('start_date')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reason</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                @error('reason')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit Application</button>
        </form>
    </div>
</div>
@endsection
