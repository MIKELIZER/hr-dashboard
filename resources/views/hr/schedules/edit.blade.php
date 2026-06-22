@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Schedule</h1>
    <a href="{{ route('hr.schedules.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.schedules.update', $schedule->id) }}" method="POST" style="max-width: 500px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Schedule Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $schedule->name) }}" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" required>
    </div>
    <div class="mb-3">
        <label for="late_tolerance" class="form-label">Late Tolerance (minutes)</label>
        <input type="number" class="form-control" id="late_tolerance" name="late_tolerance" value="{{ old('late_tolerance', $schedule->late_tolerance) }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
