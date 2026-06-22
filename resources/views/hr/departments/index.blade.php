@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Departments</h1>
    <a href="{{ route('hr.departments.create') }}" class="btn btn-primary">Add Department</a>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
            <tr>
                <td>{{ $dept->code }}</td>
                <td>{{ $dept->name }}</td>
                <td>
                    <a href="{{ route('hr.departments.edit', $dept->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('hr.departments.destroy', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
