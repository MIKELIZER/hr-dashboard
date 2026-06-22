@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Work Schedules</h1>
    <a href="{{ route('hr.schedules.create') }}" class="btn btn-primary">Add Schedule</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Late Tolerance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $sch)
            <tr>
                <td>{{ $sch->name }}</td>
                <td>{{ $sch->start_time }}</td>
                <td>{{ $sch->end_time }}</td>
                <td>{{ $sch->late_tolerance }} mins</td>
                <td>
                    <a href="{{ route('hr.schedules.edit', $sch->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('hr.schedules.destroy', $sch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
