@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Schedule</h1>
    <a href="{{ route('hr.schedules.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.schedules.store') }}" method="POST" style="max-width: 500px;">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Schedule Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
    </div>
    <div class="mb-3">
        <label for="late_tolerance" class="form-label">Late Tolerance (minutes)</label>
        <input type="number" class="form-control" id="late_tolerance" name="late_tolerance" value="{{ old('late_tolerance', 0) }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
