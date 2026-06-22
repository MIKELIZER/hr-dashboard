@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Employees</h1>
    <a href="{{ route('hr.employees.create') }}" class="btn btn-primary">Add Employee</a>
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
                <th>Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
            <tr>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->user->name ?? 'N/A' }}</td>
                <td>{{ $emp->user->email ?? 'N/A' }}</td>
                <td>{{ $emp->department->name ?? 'N/A' }}</td>
                <td>{{ $emp->position->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge bg-{{ $emp->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($emp->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('hr.employees.edit', $emp->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('hr.employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
